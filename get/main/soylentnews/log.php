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

if (!$auth_user["admin"]) {
	die("not an admin");
}

print_header("Import Log");
beg_main();

writeln('<h1>Import Log</h1>');

$items_per_page = 50;
list($item_start, $page_footer) = page_footer("soylentnews_log", $items_per_page);

$row = sql("select item_id, type, time from soylentnews_log order by time desc limit $item_start, $items_per_page");
beg_tab();
for ($i = 0; $i < count($row); $i++) {
	writeln('	<tr>');
	writeln('		<td><a href="' . item_link($row[$i]["type"], $row[$i]["item_id"]) . '">' . $row[$i]["item_id"] . '</a></td>');
	writeln('		<td class="center">' . $row[$i]["type"] . '</td>');
	writeln('		<td class="right">' . date("Y-m-d H:i", $row[$i]["time"]) . '</td>');
	writeln('	</tr>');
}
end_tab();

writeln($page_footer);

end_main();
print_footer();
