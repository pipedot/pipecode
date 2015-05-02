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

include("image.php");

if ($zid !== $auth_zid) {
	die("not your page");
}

if (!isset($_FILES["upload"])) {
	die("unknown error in upload");
}
$data = fs_slurp($_FILES["upload"]["tmp_name"]);
$src_img = @imagecreatefromstring($data);
if ($src_img === false) {
	die("unable to open uploaded file");
}
$original_width = imagesx($src_img);
$original_height = imagesy($src_img);
if ($original_width < 256 || $original_height < 256) {
	die("profile image must be at least 256 x 256");
}

$sizes = array(256, 128, 64, 32);

for ($i = 0; $i < count($sizes); $i++) {
	$tmp_img = resize_image($src_img, $sizes[$i], $sizes[$i]);
	if (!is_dir("$doc_root/www/pub/profile/$server_name")) {
		mkdir("$doc_root/www/pub/profile/$server_name", 0755, true);
	}
	imagejpeg($tmp_img, "$doc_root/www/pub/profile/$server_name/$user_page-$sizes[$i].jpg");
	imagedestroy($tmp_img);
}

$auth_user["gravatar_enabled"] = 0;
$auth_user["gravatar_seen"] = 0;
$auth_user["gravatar_sync"] = 0;
db_set_conf("user_conf", $auth_user, $auth_zid);

header("Location: /profile/");
