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

include("story.php");

$story = item_request("story");

$keywords = $story["keywords"];
//$keywords = "linux bodhi";
//$time = time() - 86400 * 30 * 12;
$beg_time = $story["publish_time"] - 86400 * 15;
$end_time = $story["publish_time"] + 86400 * 15;

print_header("Similar News");
beg_main();

writeln("<h1>Story</h1>");

writeln('<a class="icon-16 news-16" href="' . item_link("story", $story["story_id"], $story) . '">' . $story["title"]  . '</a>');

writeln("<h2>Similar News</h2>");

$items_per_page = 50;
list($item_start, $page_footer) = page_footer("select count(*) as item_count from article where match (title) against (? in boolean mode) and publish_time > ? and publish_time < ? and article.feed_id <> $server_feed_id", $items_per_page, [$keywords, $beg_time, $end_time]);

$row = sql("select article_id, author_link, author_name, article.description as description, publish_time, article.title as title, thumb_id, feed.title as feed_title, feed.slug as feed_slug from article left join feed on article.feed_id = feed.feed_id where match (article.title) against (? in boolean mode) and publish_time > ? and publish_time < ? and article.feed_id <> $server_feed_id order by publish_time desc limit $item_start, $items_per_page", $keywords, $beg_time, $end_time);
if (count($row) == 0) {
	//box_left("No articles found.");
	writeln("<p>No articles found.</p>");
} else {
	for ($i = 0; $i < count($row); $i++) {
		//writeln(date("Y-m-d", $row[$i]["publish_time"]) . " - " . $row[$i]["title"]);
		print_news($row[$i]);
	}

	writeln($page_footer);
}

end_main();
print_footer();
