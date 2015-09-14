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

if (!string_uses($s2, "[a-z]")) {
	fatal("Invalid topic");
}
$topic = $s2;
$topic = db_get_rec("topic", array("slug" => $topic));

print_header("Topics");
print_main_nav($topic);
beg_main("cell");

$items_per_page = 10;
list($item_start, $page_footer) = page_footer("story", $items_per_page, array("topic_id" => $topic["topic_id"]));

$row = sql("select story_id from story where topic_id = ? order by publish_time desc limit $item_start, $items_per_page", $topic["topic_id"]);
for ($i = 0; $i < count($row); $i++) {
	print_story($row[$i]["story_id"]);
}

writeln($page_footer);

end_main();
print_footer();

