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

include("reader.php");
include("story.php");

if ($zid !== $auth_zid) {
	die("not your page");
}

print_header("Reader", ["Add", "Edit"], ["plus", "news"], ["/reader/add", "/reader/topic/"], ["Reader"], ["/reader/"]);
print_reader_nav();
beg_main("cell");

$items_per_page = 50;
list($item_start, $page_footer) = page_footer("select count(*) as item_count from article inner join reader_user on article.feed_id = reader_user.feed_id where reader_user.zid = ?", $items_per_page, array($auth_zid));

$row = sql("select article_id, author_link, author_name, body, article.description, article.link, publish_time, article.title, thumb_id, feed.title as feed_title, feed.slug as feed_slug from article inner join feed on article.feed_id = feed.feed_id inner join reader_user on article.feed_id = reader_user.feed_id where reader_user.zid = ? order by publish_time desc limit $item_start, $items_per_page", $auth_zid);
for ($i = 0; $i < count($row); $i++) {
	print_news($row[$i]);
}

writeln($page_footer);

end_main();
print_footer();
