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

$photo = find_rec("photo");
$short_code = $photo["short_code"]

if ($photo["zid"] !== $auth_zid) {
	die("not your photo");
}

//$journal = find_rec("journal");
//if (!db_has_rec("journal_photo", array("journal_id" => $journal["journal_id"], "photo_id" => $photo_id))) {
//	die("photo_id not found in journal [$photo_id]");
//}

$path = "$doc_root/www" . public_path($photo["time"]);
$photos = array("photo_{$short_code}_128x128.jpg", "photo_{$short_code}_256x256.jpg");
$width = 320;
if ($photo["aspect_width"] == 9 && $photo["aspect_height"] == 16) {
	$photos[] = "photo_{$short_code}_320x569.jpg";
	if ($photo["has_medium"]) {
		$photos[] = "photo_{$short_code}_640x1138.jpg";
	}
	if ($photo["has_large"]) {
		$photos[] = "photo_{$short_code}_1080x1920.jpg";
	}
} else if ($photo["aspect_width"] == 3 && $photo["aspect_height"] == 4) {
	$photos[] = "photo_{$short_code}_320x427.jpg";
	if ($photo["has_medium"]) {
		$photos[] = "photo_{$short_code}_640x853.jpg";
	}
	if ($photo["has_large"]) {
		$photos[] = "photo_{$short_code}_1080x1440.jpg";
	}
} else if ($photo["aspect_width"] == 1 && $photo["aspect_height"] == 1) {
	$photos[] = "photo_{$short_code}_320x320.jpg";
	if ($photo["has_medium"]) {
		$photos[] = "photo_{$short_code}_640x640.jpg";
	}
	if ($photo["has_large"]) {
		$photos[] = "photo_{$short_code}_1080x1080.jpg";
	}
} else if ($photo["aspect_width"] == 4 && $photo["aspect_height"] == 3) {
	$photos[] = "photo_{$short_code}_320x240.jpg";
	if ($photo["has_medium"]) {
		$photos[] = "photo_{$short_code}_640x480.jpg";
	}
	if ($photo["has_large"]) {
		$photos[] = "photo_{$short_code}_1440x1080.jpg";
	}
} else if ($photo["aspect_width"] == 16 && $photo["aspect_height"] == 9) {
	$photos[] = "photo_{$short_code}_320x180.jpg";
	if ($photo["has_medium"]) {
		$photos[] = "photo_{$short_code}_640x360.jpg";
	}
	if ($photo["has_large"]) {
		$photos[] = "photo_{$short_code}_1920x1080.jpg";
	}
}

for ($i = 0; $i < count($photos); $i++) {
	fs_unlink("$path/{$photos[$i]}");
}

sql("delete from journal_photo where photo_short_id = ?", $short_id);
sql("update card set photo_short_id = 0 where photo_short_id = ?", $short_id);
//db_del_rec("journal_photo", array("journal_id" => $journal["journal_id"], "photo_id" => $photo_id));
db_del_rec("photo", $photo_id);

header("Location: " . user_page_link($auth_zid) . "menu/");
