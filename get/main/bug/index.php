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

include("bug.php");

print_header("Open Bugs", array("Report"), array("ladybug"), array("/bug/report"));
beg_main();
writeln("<h1>Open Bugs</h1>");

$items_per_page = 100;
list($item_start, $page_footer) = page_footer("bug", $items_per_page, array("closed" => 0));

$row = sql("select bug_id, author_zid, body, priority, publish_time, short_id, title from bug where closed = 0 order by publish_time desc limit $item_start, $items_per_page");
$comments = count_comments("bug", $row[$i]["bug_id"]);
beg_tab();
for ($i = 0; $i < count($row); $i++) {
	$author_zid = user_page_link($row[$i]["author_zid"], true);
	$short_code = crypt_crockford_encode($row[$i]["short_id"]);
	$icon = bug_priority_icon($row[$i]["priority"]);
	$labels = make_bug_labels($row[$i]["short_id"]);

	writeln('	<tr>');
	writeln('		<td>');
	writeln('			<div class="bug_row ' . $icon . '">');
	writeln('				<div class="bug_title"><div><a href="' . $short_code . '">' . $row[$i]["title"] . '</a></div><div>' . $labels . '</div></div>');
	writeln('				<div class="bug_subtitle">by <b>' . $author_zid . '</b> on ' . date("Y-m-d H:i", $row[$i]["publish_time"]) . ' (<a href="/' . $short_code . '">#' . $short_code . '</a>)</div>');
	writeln('			</div>');
	writeln('		</td>');
	writeln('		<td class="right"><a href="' . $short_code . '">' . $comments["tag"] . '</a></td>');
	writeln('	</tr>');
}
end_tab();

writeln($page_footer);
writeln('<div style="margin-top: 8px; margin-bottom: 8px; text-align: center"><a class="icon_16 calendar_16" href="history">History</a></div>');

end_main();
print_footer();
