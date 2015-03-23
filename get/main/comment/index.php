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

include("render.php");

print_header("Comments");
print_left_bar("main", "stories");
beg_main("cell");

writeln('<h1>Recent Comments</h1>');

$items_per_page = 50;
list($item_start, $page_footer) = page_footer("comment", $items_per_page);

if ($auth_user["show_junk_enabled"]) {
	$row = sql("select comment_id, root_id, junk_status, subject, type, edit_time, body, zid from comment order by edit_time desc limit $item_start, $items_per_page");
} else {
	$row = sql("select comment_id, root_id, junk_status, subject, type, edit_time, body, zid from comment where junk_status <= 0 order by edit_time desc limit $item_start, $items_per_page");
}
for ($i = 0; $i < count($row); $i++) {
	$a = article_info($row[$i], false);
	//print "link [{$a["link"]}] title [{$a["title"]}]";
	print render_comment($row[$i]["subject"], $row[$i]["zid"], $row[$i]["edit_time"], $row[$i]["comment_id"], $row[$i]["body"], 0, $a["link"], $a["title"], $row[$i]["junk_status"]);
	writeln('</div>');
	writeln('</article>');
	writeln();
}

writeln($page_footer);

writeln('<div style="text-align: center; margin-bottom: 8px;"><a class="icon-16 feed-16" href="atom">Comment Feed</a></div>');

end_main();
print_footer();
