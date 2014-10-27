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

if ($zid !== $auth_zid) {
	die("not your journal");
}

$journal = find_rec("journal");

print_header("Media");
beg_main();
beg_form("", "file");

writeln('<h1>Journal</h1>');
if ($journal["published"]) {
	writeln('<p><a class="icon_16 notepad_16" href="/journal/' . gmdate("Y-m-d", $journal["publish_time"]) . "/" . $journal["slug"] . '">' . $journal["title"] . '</a></p>');
} else {
	writeln('<p><a class="icon_16 notepad_16" href="/journal/' . $journal["short_code"] . '">' . $journal["title"] . '</a></p>');
}

writeln('<h2>Photos</h2>');

beg_tab();
writeln('	<tr>');
writeln('		<th>Name</th>');
writeln('		<th class="center">Size</th>');
writeln('		<th class="center">Date</th>');
writeln('		<th></th>');
writeln('	</tr>');
$row = sql("select short_id, original_name, size, time from journal_photo inner join photo on photo_short_id = short_id where journal_short_id = ?", $short_id);
if (count($row) == 0) {
	writeln('	<tr>');
	writeln('		<td colspan="4">(no photos)</th>');
	writeln('	</tr>');
}
for ($i = 0; $i < count($row); $i++) {
	$photo_short_code = crypt_crockford_encode($row[$i]["short_id"]);
	writeln('	<tr>');
	writeln('		<td><a class="icon_16 picture_16" href="' . $protocol . "://" . $server_name . "/photo/" . $photo_short_code . '">' . $row[$i]["original_name"] . '</a></td>');
	writeln('		<td class="center">' . sys_format_size($row[$i]["size"]) . '</td>');
	writeln('		<td class="center">' . date("Y-m-d H:i", $row[$i]["time"]) . '</td>');
	writeln('		<td class="right"><a class="icon_16 minus_16" href="' . $protocol . '://' . $server_name . '/photo/' . $photo_short_code . '/delete">Delete</a></td>');
	writeln('	</tr>');
}
end_tab();

left_right_box('<input name="upload" type="file"/>', "Upload");

end_form();
end_main();
print_footer();
