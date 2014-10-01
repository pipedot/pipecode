<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
//
// This file is part of Pipecode.
//
// Pipecode is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Pipecode is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Pipecode.  If not, see <http://www.gnu.org/licenses/>.
//

include("image.php");

if (!@$auth_user["editor"]) {
	die("you are not an editor");
}

if (string_uses($s2, "[A-Z][a-z][0-9]")) {
	$short_id = crypt_crockford_decode($s2);
	$short = db_get_rec("short", $short_id);
	if ($short["type"] != "story") {
		die("invalid short code [$s2]");
	}
	$story_id = $short["item_id"];
} else {
	$story_id = $s2;
}
if (!string_uses($story_id, "[a-z][0-9]_")) {
	die("invalid story_id [$story_id]");
}
$story = db_get_rec("story", $story_id);
$tmp_image_id = http_post_int("tmp_image_id");

if ($tmp_image_id > 0) {
	$image_id = promote_image($tmp_image_id);
}
$story["image_id"] = $image_id;
db_set_rec("story", $story);

header("Location: /story/$story_id");
