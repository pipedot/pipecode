<?
//
// Pipecode - distributed social network
// Copyright (C) 2014-2016 Bryan Beicker <bryan@pipedot.org>
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

include("story.php");

$keywords = http_get_string("keywords", ["required" => false, "len" => 100, "valid" => "[A-Z][a-z][0-9]+-_. "]);
$beg_time = $now - DAYS * 15;
$end_time = $now + DAYS * 15;

$spinner[] = ["name" => "Similar", "link" => "/similar"];

print_header();
beg_form("", "get");

beg_tab();
print_row(array("caption" => "Keywords", "text_key" => "keywords", "text_value" => $keywords));
end_tab();

box_right("Search");

if ($keywords != "") {
	find_server_feed_id();

	$items_per_page = 50;
	list($item_start, $page_footer) = page_footer("select count(*) as item_count from article where match (title) against (? in boolean mode) and publish_time > ? and publish_time < ? and article.feed_id <> $server_feed_id", $items_per_page, [$keywords, $beg_time, $end_time]);

	$row = sql("select article_id, author_link, author_name, article.description as description, publish_time, article.title as title, thumb_id, feed.title as feed_title, feed.slug as feed_slug from article left join feed on article.feed_id = feed.feed_id where match (article.title) against (? in boolean mode) and publish_time > ? and publish_time < ? and article.feed_id <> $server_feed_id order by publish_time desc limit $item_start, $items_per_page", $keywords, $beg_time, $end_time);
	if (count($row) == 0) {
		writeln("<p>No articles found.</p>");
	} else {
		for ($i = 0; $i < count($row); $i++) {
			print_news($row[$i]);
		}

		writeln($page_footer);
	}
}

end_form();
print_footer();
