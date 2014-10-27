<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Affero General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Affero General Public License for more details.
//
// You should have received a copy of the GNU Affero General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

function update_gravatar($zid)
{
	global $doc_root;

	$a = explode("@", $zid);
	if (count($a) != 2) {
		return false;
	}
	$user = $a[0];
	$server = $a[1];
	if (!string_uses($user, "[a-z][0-9]") || !string_uses($server, "[a-z][0-9].-") || string_has($server, "..")) {
		return false;
	}
	$user_conf = db_get_conf("user_conf", $zid);

	$url = "http://www.gravatar.com/avatar/" . md5(strtolower(trim($user_conf["email"]))) . "?s=256&d=retro&r=pg";
	$data = http_slurp($url);
	$src_img = @imagecreatefromstring($data);
	$original_width = imagesx($src_img);
	$original_height = imagesy($src_img);
	if ($original_width < 256 || $original_height < 256) {
		return false;
	}

	$sizes = array(256, 128, 64, 32);

	for ($i = 0; $i < count($sizes); $i++) {
		$tmp_img = resize_image($src_img, $sizes[$i], $sizes[$i]);
		if (!is_dir("$doc_root/www/pub/profile/$server")) {
			mkdir("$doc_root/www/pub/profile/$server", 0755, true);
		}
		imagejpeg($tmp_img, "$doc_root/www/pub/profile/$server/$user-$sizes[$i].jpg");
		imagedestroy($tmp_img);
	}
	imagedestroy($src_img);

	$user_conf["gravatar_sync"] = time();
	db_set_conf("user_conf", $user_conf, $zid);

	return true;
}


function seen_gravatar($zid)
{
	$now = time();
	$yesterday = $now - 86400;
	$user_conf = db_get_conf("user_conf", $zid);

	if (!$user_conf["gravatar_enabled"]) {
		return;
	}

	if ($user_conf["gravatar_seen"] < $yesterday) {
		$user_conf["gravatar_seen"] = $now;
		db_set_conf("user_conf", $user_conf, $zid);
		// TODO: queue gravatar update job
	}
}


function update_gravatars()
{
	$now = time();
	$yesterday = $now - 86400;

	$row = sql("select distinct zid from user_conf");
	for ($i = 0; $i < count($row); $i++) {
		$zid = $row[$i]["zid"];
		$user_conf = db_get_conf("user_conf", $zid);
		if ($user_conf["gravatar_enabled"] && $user_conf["gravatar_sync"] < $yesterday && $user_conf["gravatar_seen"] >= $user_conf["gravatar_sync"]) {
			writeln("updating [$zid]");
			update_gravatar($zid);
		}
	}
}
