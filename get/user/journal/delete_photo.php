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
$path = public_path($photo["time"]) . "/p$photo_id.256x256.jpg";
$photo_url = "$protocol://$server_name$path?" . fs_time("$doc_root/www$path");

print_header("Delete Photo");
beg_main();
beg_form();

writeln('<h1>Delete Photo</h1>');
writeln('<p>Are you sure you want to delete this photo?</p>');

writeln('<table style="border: 1px #d3d3d3 solid; margin-bottom: 8px;">');
writeln('	<tr>');
writeln('		<td style="background-color: #eeeeee; padding: 8px;"><img style="width: 128px" src="' . $photo_url . '"/></td>');
writeln('	</tr>');
writeln('</table>');

left_box("Delete");

end_form();
end_main();
print_footer();
