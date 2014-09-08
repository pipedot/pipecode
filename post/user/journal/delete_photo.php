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

if ($zid != $auth_zid) {
	die("not your journal");
}

if (string_uses($s2, "[A-Z][a-z][0-9]")) {
	$short_id = crypt_crockford_decode($s2);
	$short = db_get_rec("short", $short_id);
	if ($short["type"] != "journal") {
		die("invalid short code [$s2]");
	}
	$journal_id = $short["item_id"];
} else {
	die("invalid request");
}
$photo_id = http_get_int("photo_id");

$journal = db_get_rec("journal", $journal_id);
if (!db_has_rec("journal_photo", array("journal_id" => $journal_id, "photo_id" => $photo_id))) {
	die("photo_id not found in journal [$photo_id]");
}

$photo = db_get_rec("photo", $photo_id);
$path = "$doc_root/www" . public_path($photo["time"]);

$photos = array("p$photo_id.128x128.jpg", "p$photo_id.256x256.jpg");
$width = 320;
if ($photo["aspect_width"] == 9 && $photo["aspect_height"] == 16) {
	$photos[] = "p$photo_id.320x569.jpg";
	if ($photo["has_medium"]) {
		$photos[] = "p$photo_id.640x1138.jpg";
	}
	if ($photo["has_large"]) {
		$photos[] = "p$photo_id.1080x1920.jpg";
	}
} else if ($photo["aspect_width"] == 3 && $photo["aspect_height"] == 4) {
	$photos[] = "p$photo_id.320x427.jpg";
	if ($photo["has_medium"]) {
		$photos[] = "p$photo_id.640x853.jpg";
	}
	if ($photo["has_large"]) {
		$photos[] = "p$photo_id.1080x1440.jpg";
	}
} else if ($photo["aspect_width"] == 1 && $photo["aspect_height"] == 1) {
	$photos[] = "p$photo_id.320x320.jpg";
	if ($photo["has_medium"]) {
		$photos[] = "p$photo_id.640x640.jpg";
	}
	if ($photo["has_large"]) {
		$photos[] = "p$photo_id.1080x1080.jpg";
	}
} else if ($photo["aspect_width"] == 4 && $photo["aspect_height"] == 3) {
	$photos[] = "p$photo_id.320x240.jpg";
	if ($photo["has_medium"]) {
		$photos[] = "p$photo_id.640x480.jpg";
	}
	if ($photo["has_large"]) {
		$photos[] = "p$photo_id.1440x1080.jpg";
	}
} else if ($photo["aspect_width"] == 16 && $photo["aspect_height"] == 9) {
	$photos[] = "p$photo_id.320x180.jpg";
	if ($photo["has_medium"]) {
		$photos[] = "p$photo_id.640x360.jpg";
	}
	if ($photo["has_large"]) {
		$photos[] = "p$photo_id.1920x1080.jpg";
	}
}

for ($i = 0; $i < count($photos); $i++) {
	fs_unlink("$path/{$photos[$i]}");
}

db_del_rec("journal_photo", array("journal_id" => $journal_id, "photo_id" => $photo_id));
db_del_rec("photo", $photo_id);

header("Location: /journal/$short_code/media");
