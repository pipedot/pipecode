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

include("drive.php");

require_feature("bug");
require_developer();

$bug = item_request(TYPE_BUG);

$spinner[] = ["name" => "Bug", "link" => "/bug/"];
$spinner[] = ["name" => "Attachments", "link" => "/bug/attachments"];
$actions[] = ["name" => "Report", "icon" => "ladybug", "link" => "/bug/report"];

print_header();

writeln('<h1>Attachments</h1>');

beg_tab();
writeln('	<tr>');
writeln('		<th>File</th>');
writeln('		<th>Size</th>');
writeln('		<th>Date</th>');
writeln('		<th>Author</th>');
writeln('		<th></th>');
writeln('	</tr>');
$row = sql("select bug_file_id, name, size, time, type, zid from bug_file where bug_id = ? order by time", $bug["bug_id"]);
for ($i = 0; $i < count($row); $i++) {
	$bug_file_code = crypt_crockford_encode($row[$i]["bug_file_id"]);
	writeln('	<tr>');
	writeln('		<td><a class="icon-16 ' . file_icon($row[$i]["type"]) . '" href="/pub/bug/' . $bug_file_code . '.' . $row[$i]["type"] . '">' . $row[$i]["name"] . '</a></td>');
	writeln('		<td>' . string_size($row[$i]["size"]) . '</a></td>');
	writeln('		<td>' . date("Y-m-d H:i", $row[$i]["time"]) . '</td>');
	writeln('		<td>' . user_link($row[$i]["zid"], ["tag" => true]) . '</td>');
	writeln('		<td class="right"><a class="icon-16 delete-16" href="/bug/delete/' . $bug_file_code . '">Delete</a></td>');
	writeln('	</tr>');
}
end_tab();

box_right('<a class="icon-16 clip-16" href="/bug/' . $bug["short_code"] . '/attach">Attach</a>');

print_footer();
