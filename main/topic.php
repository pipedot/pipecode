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

$topic = $s2;

if ($topic == "") {
	print_header("Topics");
} else {
	print_header(ucwords($topic));
}

if ($topic == "") {
	print_left_bar("main", "topics");
} else {
	print_left_bar("main", $topic);
}

beg_main("cell");
if ($topic == "") {
	writeln('<h1>Topics</h1>');

	$list = db_get_list("topic", "topic");
	$k = array_keys($list);
	for ($i = 0; $i < count($list); $i++) {
		$topic = $list[$k[$i]];
		writeln('<a href="/topic/' . $topic["topic"] . '"><div class="topic_box"><img alt="' . $topic["icon"] . '" src="/images/' . $topic["icon"] . '-64.png"/>' . $topic["topic"] . '</div></a>');
	}
} else {
	$topic = db_get_rec("topic", array("topic" => $topic));

	$items_per_page = 10;
	list($item_start, $page_footer) = page_footer("story", $items_per_page, array("tid" => $topic["tid"]));

	$row = run_sql("select sid from story where tid = ? order by sid desc limit $item_start, $items_per_page", array($topic["tid"]));
	for ($i = 0; $i < count($row); $i++) {
		print_story($row[$i]["sid"]);
	}

	writeln($page_footer);
}
end_main();

print_footer();
