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

print_header("Comments");
beg_main();

writeln('<h1>' . get_text('Recent Comments') . '</h1>');

$items_per_page = 50;
list($item_start, $page_footer) = page_footer("comment", $items_per_page);

if ($auth_user["show_junk_enabled"]) {
	$row = sql("select comment_id, article_id, junk_status, subject, edit_time, body, zid from comment order by edit_time desc limit $item_start, $items_per_page");
} else {
	$row = sql("select comment_id, article_id, junk_status, subject, edit_time, body, zid from comment where junk_status <= 0 order by edit_time desc limit $item_start, $items_per_page");
}
for ($i = 0; $i < count($row); $i++) {
	print_comment($row[$i], true);
}

writeln($page_footer);

box_center('<a class="icon-16 feed-16" href="atom">Comment Feed</a>');

end_main();
print_footer();
