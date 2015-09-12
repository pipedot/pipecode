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
include("poll.php");

print_header();
print_main_nav("stories");
beg_main("cell");

$items_per_page = 10;
list($item_start, $page_footer) = page_footer("story", $items_per_page);

$row = sql("select story_id from story order by publish_time desc limit $item_start, $items_per_page");
for ($i = 0; $i < count($row); $i++) {
	print_story($row[$i]["story_id"]);
}

writeln($page_footer);

end_main();
writeln('<aside>');

if ($auth_zid != "") {
	print_user_box();
	print_notification_box();
} else {
	writeln('<div style="width: 300px">');
}

$row = sql("select poll_id from poll where promoted = 1 order by publish_time desc limit 1");
if (count($row) > 0) {
	$poll_id = $row[0]["poll_id"];
	if ($auth_zid === "") {
		$vote = false;
	} else {
		$row = sql("select count(*) as answers from poll_vote where poll_id = ? and zid = ?", $poll_id, $auth_zid);
		$vote = $row[0]["answers"] == 0;
	}
	vote_box($poll_id, $vote);
}

writeln('<div class="dialog-title">Recent Journals</div>');
writeln('<div class="dialog-body">');
$row = sql("select publish_time, slug, title, zid from journal where published = 1 order by publish_time desc limit 0, 5");
for ($i = 0; $i < count($row); $i++) {
	writeln('	<table class="recent-journal">');
	writeln('		<tr>');
	writeln('			<td><a href="' . user_link($row[$i]["zid"]) . '"><img src="' . avatar_picture($row[$i]["zid"], 64) . '"></a></td>');
	writeln('			<td>');
	writeln('				<div class="recent-journal-title"><a href="' . user_link($row[$i]["zid"]) . 'journal/' . gmdate("Y-m-d", $row[$i]["publish_time"]) . '/' . $row[$i]["slug"] . '">' . $row[$i]["title"] . '</a></div>');
	writeln('				<div class="recent-journal-author"><a href="' . user_link($row[$i]["zid"]) . '">' . $row[$i]["zid"] . '</a></div>');
	writeln('			</td>');
	writeln('		</tr>');
	writeln('	</table>');
}
writeln('</div>');

writeln('<div class="dialog-title">Most Discussed</div>');
writeln('<div class="dialog-body">');
$row = sql("select * from (select * from (select story_id, title, slug, story.publish_time, count(comment_id) as comments from story left join comment on story.story_id = comment.root_id group by story_id order by story.publish_time desc limit 100) as most_discussed order by comments desc limit 5) as top_five order by publish_time desc");
writeln('	<ul class="popular">');
for ($i = 0; $i < count($row); $i++) {
	writeln('		<li>');
	writeln('			<div><div class="popular-count">' . $row[$i]["comments"] . '</div></div>');
	writeln('			<div class="popular-title"><a href="/story/' . gmdate("Y-m-d", $row[$i]["publish_time"]) . '/' . $row[$i]["slug"] . '">' . $row[$i]["title"] . '</a></div>');
	writeln('		</li>');
}
writeln('	</ul>');
writeln('</div>');

if ($auth_zid === "") {
	writeln('</div>');
}

writeln('</aside>');

print_footer();

