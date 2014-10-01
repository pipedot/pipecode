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

$journal = db_get_rec("journal", $journal_id);

print_header("Media");
beg_main();
beg_form("", "file");

writeln('<h1>Journal</h1>');
if ($journal["published"]) {
	writeln('<p><a class="icon_16 notepad_16" href="/journal/' . gmdate("Y-m-d", $journal["publish_time"]) . "/" . $journal["slug"] . '">' . $journal["title"] . '</a></p>');
} else {
	writeln('<p><a class="icon_16 notepad_16" href="/journal/' . crypt_crockford_encode($journal["short_id"]) . '">' . $journal["title"] . '</a></p>');
}

writeln('<h2>Photos</h2>');

beg_tab();
writeln('	<tr>');
writeln('		<th>Name</th>');
writeln('		<th class="center">Size</th>');
writeln('		<th class="center">Date</th>');
writeln('		<th></th>');
writeln('	</tr>');
$row = sql("select journal_photo.photo_id, original_name, size, time from journal_photo inner join photo on journal_photo.photo_id = photo.photo_id where journal_id = ?", $journal_id);
if (count($row) == 0) {
	writeln('	<tr>');
	writeln('		<td colspan="4">(no photos)</th>');
	writeln('	</tr>');
}
for ($i = 0; $i < count($row); $i++) {
	writeln('	<tr>');
	writeln('		<td><a class="icon_16 picture_16" href="' . $protocol . "://" . $server_name . "/photo/" . $row[$i]["photo_id"] . '">' . $row[$i]["original_name"] . '</a></td>');
	writeln('		<td class="center">' . sys_format_size($row[$i]["size"]) . '</td>');
	writeln('		<td class="center">' . date("Y-m-d H:i", $row[$i]["time"]) . '</td>');
	writeln('		<td class="right"><a class="icon_16 minus_16" href="delete_photo?photo_id=' . $row[$i]["photo_id"] . '">Delete</a></td>');
	writeln('	</tr>');
}
end_tab();

left_right_box('<input name="upload" type="file"/>', "Upload");

end_form();
end_main();
print_footer();

