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

include("image.php");

if ($auth_zid === "") {
	die("sign in to attach");
}

$bug = find_rec("bug");

if (isset($_FILES["upload"]) && $_FILES["upload"]["tmp_name"] != "") {
	$bug_file["name"] = string_clean($_FILES["upload"]["name"], "[A-Z][a-z][0-9].-_", 100);
	$bug_file["type"] = fs_ext($bug_file["name"]);
	$bug_file["size"] = fs_size($_FILES["upload"]["tmp_name"]);
	$bug_file["hash"] = crypt_sha256_file($_FILES["upload"]["tmp_name"]);
} else {
	die("no upload");
}
if ($bug_file["type"] === "php") {
	die("invalid file type");
}

$bug_file = db_new_rec("bug_file");
$bug_file["long_id"] = create_id($auth_zid, $now);
$bug_file["short_id"] = create_short("bug_file", $bug_file["long_id"]);
$bug_file["bug_short_id"] = $bug["short_id"];
$bug_file["remote_ip"] = $remote_ip;
$bug_file["server"] = $server_name;
$bug_file["zid"] = $auth_zid;

if ($bug_file["type"] == "jpg" || $bug_file["type"] == "png") {
	$data = fs_slurp($_FILES["upload"]["tmp_name"]);
	$src_img = @imagecreatefromstring($data);
	if ($src_img === false) {
		die("unable to open uploaded image");
	}
	if (imagesx($src_img) < 256 || imagesy($src_img) < 256) {
		die("images must be at least 256 x 256");
	}
	$path = $doc_root . "/www" . public_path($now);
	if (!is_dir($path)) {
		mkdir($path, 0755, true);
	}
	$tmp_img = resize_image($src_img, 128, 128);
	imagejpeg($tmp_img, "$path/bug_file_{$bug["short_code"]}_128x128.jpg");
	imagedestroy($tmp_img);
	$tmp_img = resize_image($src_img, 256, 256);
	imagejpeg($tmp_img, "$path/bug_file_{$bug["short_code"]}_256x256.jpg");
	imagedestroy($tmp_img);
}
if (!move_uploaded_file($_FILES["upload"]["tmp_name"], "$doc_root/www/pub/bug/{$bug["short_code"]}." . $bug_file["type"])) {
	die("upload failed");
}

db_set_rec("bug_file", $bug_file);

header("Location: /bug/" . crypt_crockford_encode($bug["short_id"]));