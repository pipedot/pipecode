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

$spinner[] = ["name" => "Poll", "link" => "/poll/"];

print_header(["title" => "Polls"]);

$items_per_page = 50;
list($item_start, $page_footer) = page_footer("poll", $items_per_page);

dict_beg();
$row = sql("select question, slug, publish_time from poll order by publish_time desc limit $item_start, $items_per_page");
for ($i = 0; $i < count($row); $i++) {
	$date = gmdate("Y-m-d", $row[$i]["publish_time"]);
	dict_row('<a href="' . $date . "/" . $row[$i]["slug"] . '">' . $row[$i]["question"] . '</a>', $date);
}
dict_end();

writeln($page_footer);

print_footer();
