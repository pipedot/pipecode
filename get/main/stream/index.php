<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
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

include("stream.php");
include("image.php");

if ($auth_zid === "") {
	print_header("Stream");
} else {
	print_header("Stream", array("Share"), array("share"), array(user_page_link($auth_zid) . "stream/share"));
}

beg_main("stream");

//$row = sql("select card_id from card inner join article on card.article_id = article.article_id order by time desc");
$row = sql("select short_id from card order by edit_time desc");

for ($i = 0; $i < count($row); $i++) {
	print_card($row[$i]["short_id"]);
}

end_main("stream");

print_footer();



