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

include("stream.php");
include("image.php");

$tag = $s2;
if (!string_uses($tag, "[a-z][0-9]")) {
	fatal("Invalid tag");
}
//$tag = db_get_rec("tag", array("tag" => $tag));
//$tag_id = $tag["tag_id"];

if ($auth_zid === "") {
	print_header("#$tag");
} else {
	print_header("#$tag", array("Share"), array("share"), array(user_link($auth_zid) . "stream/share"));
}

beg_main("stream");

$row = sql("select card.card_id from card inner join card_tags on card.card_id = card_tags.card_id where tag = ? order by edit_time desc", $tag);
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

