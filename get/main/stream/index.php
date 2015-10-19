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

include("image.php");

if ($auth_zid === "") {
	print_header("Stream");
} else {
	print_header("Stream", array("Share"), array("share"), array(user_link($auth_zid) . "stream/share"));
}

beg_main("stream");

$items_per_page = 50;
list($item_start, $page_footer) = page_footer("stream_main", $items_per_page);

$row = sql("select article_id from stream_main order by time desc limit $item_start, $items_per_page");
for ($i = 0; $i < count($row); $i++) {
	print_card($row[$i]["article_id"]);
}

end_main("stream");
writeln($page_footer);
print_footer();
