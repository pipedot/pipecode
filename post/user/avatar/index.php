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
include("drive.php");
include("avatar.php");

if ($zid !== $auth_zid) {
	die("not your page");
}
list($user, $domain) = explode("@", $zid);

if (!isset($_FILES["upload"])) {
	die("unknown error in upload");
}
$data = fs_slurp($_FILES["upload"]["tmp_name"]);
$src_img = @imagecreatefromstring($data);
if ($src_img === false) {
	die("unable to open uploaded file");
}
$avatar_id = create_image_avatar($auth_zid, $src_img);
imagedestroy($src_img);

$user_conf["avatar_id"] = $avatar_id;
db_set_conf("user_conf", $user_conf, $auth_zid);

header("Location: /avatar/");

