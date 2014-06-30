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

$row = run_sql("select sid from story order by sid desc limit $item_start, $items_per_page");
for ($i = 0; $i < count($row); $i++) {
	print_story($row[$i]["sid"]);
}

writeln($page_footer);

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

