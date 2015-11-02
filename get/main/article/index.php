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

print_header("Articles");
beg_main();

writeln('<h1>' . get_text('Articles') . '</h1>');

$items_per_page = 50;
list($item_start, $page_footer) = page_footer("article", $items_per_page);

dict_beg();
$row = sql("select * from article order by publish_time desc limit $item_start, $items_per_page");
for ($i = 0; $i < count($row); $i++) {
	dict_row('<a href="/article/' . crypt_crockford_encode($row[$i]["article_id"]) . '">' . $row[$i]["title"] . '</a>', date("Y-m-d H:i", $row[$i]["publish_time"]));
}
dict_end();

writeln($page_footer);

end_main();
print_footer();
