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

include("render.php");
include("story.php");

print_header("Journal", array("Write"), array("notepad"), array("/journal/write"));
print_left_bar("user", "journal");
beg_main("cell");

$items_per_page = 20;
if ($zid == $auth_zid) {
	list($item_start, $page_footer) = page_footer("journal", $items_per_page, array("zid" => $zid));
	$row = sql("select journal_id from journal where zid = ? order by published, publish_time desc limit $item_start, $items_per_page", $zid);
} else {
	list($item_start, $page_footer) = page_footer("journal", $items_per_page, array("zid" => $zid, "published" => 1));
	$row = sql("select journal_id from journal where zid = ? and published = 1 order by publish_time desc limit $item_start, $items_per_page", $zid);
}
if (count($row) == 0) {
	if ($zid == $auth_zid) {
		writeln('<p>No journal entries yet! <a href="write">Write</a> one now!</p>');
	} else {
		writeln('<p>No journal entries yet!</p>');
	}
}
for ($i = 0; $i < count($row); $i++) {
	print_journal($row[$i]["journal_id"]);
}

writeln($page_footer);

end_main();
print_footer();
