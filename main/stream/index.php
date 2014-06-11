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

include("stream.php");

if ($auth_zid == "") {
	print_header("Stream");
} else {
	print_header("Stream", array("Share"), array("notepad"), array(user_page_link($auth_zid) . "stream/share"));
}

beg_main("stream");

$row = run_sql("select card_id from card inner join article on card.article_id = article.article_id order by time desc");

for ($i = 0; $i < count($row); $i++) {
	print_card($row[$i]["card_id"]);
}

end_main("stream");

print_footer();



