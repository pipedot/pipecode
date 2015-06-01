<?
//
// Pipecode - distributed social network
// Copyright (C) 2014-2015 Bryan Beicker <bryan@pipedot.org>
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

include("drive.php");
include("avatar.php");

if (string_uses($s2, "[A-Z][0-9]")) {
	print_header("Avatar");
	beg_main();

	$avatar = item_request(TYPE_AVATAR);
	$avatar_code = $avatar["short_code"];

	writeln('<h1>Avatar</h1>');
	writeln('<div class="photo-frame">');
	writeln('	<img alt="avatar" class="thumb" src="' . $avatar_code . '-256.jpg">');
	writeln('	<div><a href="' . $avatar_code . '-64.png">Small (64x64)</a> | <a href="' . $avatar_code . '-128.jpg">Medium (128x128)</a> | <a href="' . $avatar_code . '-256.jpg">Large (256x256)</a></div>');
	writeln('</div>');

	dict_beg();
	dict_row("Author", user_link($avatar["zid"], ["tag" => true]));
	dict_row("Time", date("Y-m-d H:i", $avatar["time"]));
	dict_row("License", '<a href="https://creativecommons.org/licenses/by-sa/4.0/">CC BY-SA 4.0</a>');
	dict_end();

	end_main();
	print_footer();
	die();
}

$ext = fs_ext($s2);
$s = substr($s2, 0, -1 - strlen($ext));

$pos = strrpos($s, "-");
if ($pos === false) {
	die("filename must include size");
}
$size = substr($s, $pos + 1);
if ($size == 64) {
	if ($ext != "png") {
		die("filename must end in .png");
	}
} else if ($size == 128 || $size == 256) {
	if ($ext != "jpg") {
		die("filename must end in .jpg");
	}
} else {
	die("invalid request $size $ext");
}
$s = substr($s, 0, $pos);

if (!string_uses($s, "[A-Z][0-9]")) {
	die("invalid short code");
}
$avatar_id = crypt_crockford_decode($s);
$avatar = db_get_rec("avatar", $avatar_id);
$hash = $avatar["hash_$size"];

if (!http_modified($avatar["time"], $hash)) {
	http_response_code(304);
} else {
	$data = drive_get($hash);
	if ($size == 64) {
		header("Content-type: image/jpeg");
	} else {
		header("Content-type: image/png");
	}
	header("Content-length: " . strlen($data));
	header("Last-Modified: " . gmdate("D, j M Y H:i:s", $avatar["time"]) . " GMT");
	header("ETag: \"$hash\"");

	print $data;
}
