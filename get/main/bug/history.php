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

print_header("Closed Bugs", array("Report"), array("ladybug"), array("/bug/report"));
beg_main();
writeln("<h1>Closed Bugs</h1>");

$items_per_page = 100;
list($item_start, $page_footer) = page_footer("bug", $items_per_page, array("closed" => 1));

$row = sql("select bug_id, author_zid, body, publish_time, short_id, title from bug where closed = 1 order by publish_time desc limit $item_start, $items_per_page");
beg_tab();
writeln('	<tr>');
writeln('		<th>Bug</th>');
writeln('		<th>Title</th>');
writeln('		<th>Reporter</th>');
writeln('		<th>Labels</th>');
writeln('		<th>Date</th>');
writeln('	</tr>');
for ($i = 0; $i < count($row); $i++) {
	$author_zid = user_page_link($row[$i]["author_zid"], true);
	$short_code = crypt_crockford_encode($row[$i]["short_id"]);
	$labels = "";

	writeln('	<tr>');
	writeln('		<td><a href="' . $short_code . '">' . $short_code . '</a></td>');
	writeln('		<td>' . $row[$i]["title"] . '</td>');
	writeln('		<td>' . $author_zid . '</td>');
	writeln('		<td>' . $labels . '</td>');
	writeln('		<td style="white-space: nowrap">' . date("Y-m-d H:i", $row[$i]["publish_time"]) . '</td>');
	writeln('	</tr>');
}
end_tab();

writeln($page_footer);
writeln('<div style="margin-top: 8px; margin-bottom: 8px; text-align: center"><a class="icon_16 calendar_16" href="history">History</a></div>');

end_main();
print_footer();
