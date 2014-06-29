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

$page = http_get_int("page", array("default" => 1, "required" => false));

print_header();

print_left_bar("main", "stories");
beg_main("cell");

$stories_per_page = 10;
$row = run_sql("select count(sid) as story_count from story");
$story_count = (int) $row[0]["story_count"];
$pages_count = ceil($story_count / $stories_per_page);
$story_start = ($page - 1) * $stories_per_page;

$row = run_sql("select sid from story order by sid desc limit $story_start, $stories_per_page");
for ($i = 0; $i < count($row); $i++) {
	print_story($row[$i]["sid"], "middle");
}

$s = "";
//$s .= "<a class=\"pages_first\" href=\"?page=1\"></a>";
if ($page > 1) {
	$s .= "<a class=\"pages_left\" href=\"?page=" . ($page - 1) . "\" title=\"Back\"></a>";
}
for ($i = 1; $i <= $pages_count; $i++) {
	if ($i == $page) {
		//$s .= "$i ";
		$s .= "<span>$i</span>";
	} else {
		$s .= "<a href=\"?page=$i\">$i</a>";
	}
}
if ($page < $pages_count) {
	$s .= "<a class=\"pages_right\" href=\"?page=" . ($page + 1) . "\" title=\"Next\"></a>";
}
//$s .= "<a class=\"pages_last\" href=\"?page=1\"></a>";
writeln('<div class="pages">' . trim($s) . '</div>');

end_main();
writeln('<aside>');

if ($auth_zid != "") {
	print_user_box();
}

$row = run_sql("select max(qid) as qid from poll_question");
$qid = $row[0]["qid"];
vote_box($qid, false, true);

writeln('</aside>');

print_footer();

