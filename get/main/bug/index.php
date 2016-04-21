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

include("bug.php");

require_feature("bug");

$spinner[] = ["name" => "Bug", "link" => "/bug/"];
$actions[] = ["name" => "Report", "icon" => "ladybug", "link" => "/bug/report"];

print_header(["title" => "Open Bugs"]);
writeln('<h1>' . get_text('Open Bugs') . '</h1>');

$items_per_page = 100;
list($item_start, $page_footer) = page_footer("bug", $items_per_page, array("closed" => 0));

$row = sql("select bug_id, author_zid, body, priority, publish_time, title from bug where closed = 0 order by publish_time desc limit $item_start, $items_per_page");
beg_tab();
for ($i = 0; $i < count($row); $i++) {
	$author_zid = user_link($row[$i]["author_zid"], ["tag" => true]);
	$bug_code = crypt_crockford_encode($row[$i]["bug_id"]);
	$icon = bug_priority_icon($row[$i]["priority"]);
	$labels = make_bug_labels($row[$i]["bug_id"]);
	$comments = count_comments($row[$i]["bug_id"], TYPE_BUG);

	writeln('	<tr>');
	writeln('		<td>');
	writeln('			<div class="bug-row ' . $icon . '">');
	writeln('				<div class="bug-title"><div><a href="' . $bug_code . '">' . $row[$i]["title"] . '</a></div><div>' . $labels . '</div></div>');
	writeln('				<div class="bug-subtitle">by <b>' . $author_zid . '</b> on ' . date("Y-m-d H:i", $row[$i]["publish_time"]) . ' (<a href="/' . $bug_code . '">#' . $bug_code . '</a>)</div>');
	writeln('			</div>');
	writeln('		</td>');
	writeln('		<td class="right"><a href="' . $bug_code . '">' . $comments["tag"] . '</a></td>');
	writeln('	</tr>');
}
end_tab();

writeln($page_footer);
box_center('<a class="icon-16 calendar-16" href="history">' . get_text('History') . '</a>');

print_footer();
