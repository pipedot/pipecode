<?
//
// Pipecode - distributed social network
// Copyright (C) 2014-2016 Bryan Beicker <bryan@pipedot.org>
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

include("clean.php");
include("image.php");

require_mine();

list($clean_body, $dirty_body) = clean_body(false);
$tags = clean_tags();
$link_url = clean_link();
$time = time();

if (isset($_FILES["upload"]) && $_FILES["upload"]["tmp_name"] != "") {
	$data = fs_slurp($_FILES["upload"]["tmp_name"]);
	$hash = crypt_sha256($data);
	$src_img = @imagecreatefromstring($data);
	if ($src_img === false) {
		fatal("Unable to open uploaded file");
	}
	$photo_id = create_photo($src_img, $_FILES["upload"]["name"], $hash);
} else {
	$photo_id = 0;
}

if ($link_url == "") {
	$link_subject = "";
} else {
	$link_subject = slurp_title($link_url);
	if ($link_subject === false) {
		$link_url = "";
	}
}

if ($clean_body == "" && $link_url == "" && $photo_id == 0) {
	fatal("Nothing to share");
}

$card = db_new_rec("card");
$card["card_id"] = create_short(TYPE_CARD);
$card["image_id"] = 0;
$card["archive"] = $time + DAYS * 90;
$card["body"] = $clean_body;
$card["link_subject"] = $link_subject;
$card["link_url"] = $link_url;
$card["photo_id"] = $photo_id;
$card["zid"] = $auth_zid;

db_set_rec("card", $card);
$card_code = crypt_crockford_encode($card["card_id"]);

for ($i = 0; $i < count($tags); $i++) {
	$card_tags = db_new_rec("card_tags");
	$card_tags["card_id"] = $card["card_id"];
	$card_tags["tag"] = $tags[$i];
	db_set_rec("card_tags", $card_tags);
}

$stream_user = db_new_rec("stream_user");
$stream_user["zid"] = $auth_zid;
$stream_user["article_id"] = $card["card_id"];
db_set_rec("stream_user", $stream_user);

if ($link_url == "") {
	header("Location: " . user_link($auth_zid) . "stream/");
} else {
	header("Location: $protocol://$server_name/card/$card_code/image");
}

