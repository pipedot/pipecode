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
	die("not your journal");
}

$journal = item_request(TYPE_JOURNAL);

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

if ($photo_id > 0) {
	sql("insert into journal_photo (journal_id, photo_id) values (?, ?)", $journal["journal_id"], $photo_id);
}

header("Location: /journal/{$journal["short_code"]}/media");
