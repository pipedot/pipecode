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

require_feature("bug");

$spinner[] = ["name" => "Bug", "link" => "/bug/"];
$spinner[] = ["name" => "Closed Bugs", "link" => "/bug/history"];
$actions[] = ["name" => "Report", "icon" => "ladybug", "link" => "/bug/report"];

print_header();
writeln('<h1>' . get_text('Closed Bugs') . '</h1>');

$items_per_page = 100;
list($item_start, $page_footer) = page_footer("bug", $items_per_page, array("closed" => 1));

$row = sql("select bug_id, author_zid, body, publish_time, title from bug where closed = 1 order by publish_time desc limit $item_start, $items_per_page");
beg_tab();
writeln('	<tr>');
writeln('		<th>' . get_text('Bug') . '</th>');
writeln('		<th>' . get_text('Title') . '</th>');
writeln('		<th>' . get_text('Reporter') . '</th>');
writeln('		<th>' . get_text('Labels') . '</th>');
writeln('		<th>' . get_text('Date') . '</th>');
writeln('	</tr>');
for ($i = 0; $i < count($row); $i++) {
	$author_zid = user_link($row[$i]["author_zid"], ["tag" => true]);
	$bug_code = crypt_crockford_encode($row[$i]["bug_id"]);
	$labels = "";

	writeln('	<tr>');
	writeln('		<td><a href="' . $bug_code . '">' . $bug_code . '</a></td>');
	writeln('		<td>' . $row[$i]["title"] . '</td>');
	writeln('		<td>' . $author_zid . '</td>');
	writeln('		<td>' . $labels . '</td>');
	writeln('		<td style="white-space: nowrap">' . date("Y-m-d H:i", $row[$i]["publish_time"]) . '</td>');
	writeln('	</tr>');
}
end_tab();

writeln($page_footer);
box_center('<a class="icon-16 calendar-16" href="history">' . get_text('History') . '</a>');

print_footer();
