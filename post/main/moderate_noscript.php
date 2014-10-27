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

include("render.php");

if ($auth_zid === "") {
	die("error: sign in to moderate");
}

$root_id = http_post_string("root_id", array("required" => false, "valid" => "[a-z][0-9]_"));
$type = http_post_string("type");
check_article_type($type);

function moderate($comment_id, $zid, $reason)
{
	global $reasons;

	if (!array_key_exists($reason, $reasons)) {
		return;
	}
	if (!db_has_rec("comment", $comment_id)) {
		return;
	}
	if (db_has_rec("comment_vote", array("comment_id" => $comment_id, "zid" => $zid))) {
		$comment_vote = db_get_rec("comment_vote", array("comment_id" => $comment_id, "zid" => $zid));
		$old = $comment_vote["reason"];
		if ($reason == $old) {
			return;
		} else if ($reason == "Normal") {
			db_del_rec("comment_vote", array("comment_id" => $comment_id, "zid" => $zid));
		} else {
			$comment_vote["reason"] = $reason;
			$comment_vote["value"] = $reasons[$reason];
			$comment_vote["time"] = time();
			db_set_rec("comment_vote", $comment_vote);
			return;
		}
	}
	if ($reason == "Normal") {
		return;
	}
	$comment_vote = array();
	$comment_vote["comment_id"] = $comment_id;
	$comment_vote["reason"] = $reason;
	$comment_vote["time"] = time();
	$comment_vote["value"] = $reasons[$reason];
	$comment_vote["zid"] = $zid;
	db_set_rec("comment_vote", $comment_vote);
}

$k = array_keys($_POST);
for ($i = 0; $i < count($k); $i++) {
	if (substr($k[$i], 0, 8) == "comment_") {
		$comment_id = substr($k[$i], 8);
		$reason = $_POST[$k[$i]];

		moderate($comment_id, $auth_zid, $reason);
	}
}

header("Location: " . item_link($type, $root_id));
