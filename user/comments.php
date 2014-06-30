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

print_header("Comments");
print_left_bar("user", "comments");
beg_main("cell");

writeln('<h1>Comments</h1>');

$items_per_page = 50;
list($item_start, $page_footer) = page_footer("comment", $items_per_page, array("zid" => $zid));

$row = run_sql("select cid, subject, time, comment from comment where zid = ? order by time desc limit $item_start, $items_per_page", array($zid));
for ($i = 0; $i < count($row); $i++) {
	print render_comment($row[$i]["subject"], $zid, $row[$i]["time"], $row[$i]["cid"], $row[$i]["comment"]);
	writeln('</div>');
	writeln('</article>');
	writeln();
}

writeln($page_footer);

end_main();
print_footer();
