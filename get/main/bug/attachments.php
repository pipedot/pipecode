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

include("file.php");

if (!$auth_user["editor"] && !$auth_user["admin"]) {
	die("not an editor or an admin");
}

$bug = find_rec("bug");

print_header("Attachments", array("Report"), array("ladybug"), array("/bug/report"));
beg_main();

writeln('<h1>Attachments</h1>');

beg_tab();
writeln('	<tr>');
writeln('		<th>File</th>');
writeln('		<th>Size</th>');
writeln('		<th>Date</th>');
writeln('		<th>Author</th>');
writeln('		<th></th>');
writeln('	</tr>');
$row = sql("select short_id, name, size, time, type, zid from bug_file where bug_short_id = ? order by time", $short_id);
for ($i = 0; $i < count($row); $i++) {
	$bug_file_short_code = crypt_crockford_encode($row[$i]["short_id"]);
	writeln('	<tr>');
	writeln('		<td><a class="icon_16 ' . file_icon($row[$i]["type"]) . '" href="/pub/bug/' . $bug_file_short_code . '.' . $row[$i]["type"] . '">' . $row[$i]["name"] . '</a></td>');
	writeln('		<td>' . sys_format_size($row[$i]["size"]) . '</a></td>');
	writeln('		<td>' . date("Y-m-d H:i", $row[$i]["time"]) . '</td>');
	writeln('		<td>' . user_page_link($row[$i]["zid"], true) . '</td>');
	writeln('		<td class="right"><a class="icon_16 delete_16" href="/bug/delete/' . $bug_file_short_code . '">Delete</a></td>');
	writeln('	</tr>');
}
end_tab();

right_box('<a class="icon_16 clip_16" href="/bug/' . $bug["short_code"] . '/attach">Attach</a>');

end_main();
print_footer();

