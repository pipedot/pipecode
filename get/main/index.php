<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
//
// This file is part of Pipecode.
//
// Pipecode is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Pipecode is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Pipecode.  If not, see <http://www.gnu.org/licenses/>.
//

include("story.php");
include("poll.php");

print_header();

print_left_bar("main", "stories");
beg_main("cell");

$items_per_page = 10;
list($item_start, $page_footer) = page_footer("story", $items_per_page);

if ($auth_user["soylentnews_enabled"]) {
	$row = sql("select story_id from story order by publish_time desc limit $item_start, $items_per_page");
} else {
	$row = sql("select story_id from story where author_zid not like '%$import_server_name' order by publish_time desc limit $item_start, $items_per_page");
}
for ($i = 0; $i < count($row); $i++) {
	print_story($row[$i]["story_id"]);
}

writeln($page_footer);

end_main();
writeln('<aside>');

if ($auth_zid != "") {
	print_user_box();
} else {
	writeln('<div style="width: 300px">');
}

$row = sql("select poll_id from poll order by time desc limit 1");
$poll_id = $row[0]["poll_id"];
if ($auth_zid == "") {
	$vote = false;
} else {
	$row = sql("select count(*) as answers from poll_vote where poll_id = ? and zid = ?", $poll_id, $auth_zid);
	$vote = $row[0]["answers"] == 0;
}
vote_box($poll_id, $vote);

writeln('<div class="dialog_title">Most Discussed</div>');
writeln('<div class="dialog_body">');
if ($auth_user["soylentnews_enabled"]) {
	$row = sql("select * from (select * from (select story_id, title, slug, publish_time, count(comment_id) as comments from story left join comment on story.story_id = comment.root_id where type = 'story' group by story_id order by publish_time desc limit 100) as most_discussed order by comments desc limit 5) as top_five order by publish_time desc");
} else {
	$row = sql("select * from (select * from (select story_id, title, slug, publish_time, count(comment_id) as comments from story left join comment on story.story_id = comment.root_id where type = 'story' and author_zid not like '%$import_server_name' group by story_id order by publish_time desc limit 100) as most_discussed order by comments desc limit 5) as top_five order by publish_time desc");
}
writeln('	<ul class="popular">');
for ($i = 0; $i < count($row); $i++) {
	writeln('		<li>');
	writeln('			<div><div class="popular_count">' . $row[$i]["comments"] . '</div></div>');
	writeln('			<div class="popular_title"><a href="/story/' . date("Y-m-d", $row[$i]["publish_time"]) . '/' . $row[$i]["slug"] . '">' . $row[$i]["title"] . '</a></div>');
	writeln('		</li>');
}
writeln('	</ul>');
writeln('</div>');

if ($auth_zid == "") {
	writeln('</div>');
}

writeln('</aside>');

print_footer();

