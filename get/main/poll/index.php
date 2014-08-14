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

print_header("Polls");

print_left_bar("main", "poll");
beg_main("cell");

writeln('<h1>Polls</h1>');

$items_per_page = 50;
list($item_start, $page_footer) = page_footer("poll", $items_per_page);

writeln('<table class="zebra">');
$row = sql("select question, slug, time from poll order by time desc limit $item_start, $items_per_page");
for ($i = 0; $i < count($row); $i++) {
	$date = gmdate("Y-m-d", $row[$i]["time"]);

	writeln('	<tr>');
	writeln('		<td><a href="' . $date . "/" . $row[$i]["slug"] . '">' . $row[$i]["question"] . '</a></td>');
	writeln('		<td style="text-align: right; white-space: nowrap;">' . $date . '</td>');
	writeln('	</tr>');
}
writeln('</table>');

writeln($page_footer);

end_main();
print_footer();
