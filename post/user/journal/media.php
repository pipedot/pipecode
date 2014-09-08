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

$journal = db_get_rec("journal", $journal_id);
$short_code = crypt_crockford_encode($journal["short_id"]);

if (isset($_FILES["upload"]) && $_FILES["upload"]["tmp_name"] != "") {
	$data = fs_slurp($_FILES["upload"]["tmp_name"]);
	$hash = crypt_sha256($data);
	$src_img = @imagecreatefromstring($data);
	if ($src_img === false) {
		die("unable to open uploaded file");
	}
	$photo_id = create_photo($src_img, $_FILES["upload"]["name"], $hash);
} else {
	$photo_id = 0;
}

sql("insert into journal_photo (journal_id, photo_id) values (?, ?)", $journal_id, $photo_id);

header("Location: /journal/$short_code/media");
