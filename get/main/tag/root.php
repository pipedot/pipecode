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

$tag_name = $s2;
$tag = db_get_rec("tag", array("tag" => $tag_name));
$tag_id = $tag["tag_id"];

print_header("#$s2", array("Share"), array("notepad"), array(user_page_link($auth_zid) . "stream/share"));

beg_main("stream");

$row = sql("select card.card_id from card inner join card_tags on card.card_id = card_tags.card_id where tag_id = ? order by time desc", $tag_id);
//$row = sql("select card.card_id from card inner join card_tags on card.card_id = card_tags.card_id order by time desc");
//var_dump($row);
//print "tag_id [$tag_id]";

for ($i = 0; $i < count($row); $i++) {
	//print "card_id [" . $row[$i]["card_id"] . "]";
	print_card($row[$i]["card_id"]);
}

//writeln('<h1>Tag</h1>');

//print "s2 [$s2]";

end_main("stream");
print_footer();

