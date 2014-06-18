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

print_header("Stream", array("Share"), array("notepad"), array("/stream/share"));

beg_main("stream");

//$row = run_sql("select card_id from card inner join article on card.article_id = article.article_id where zid = ? order by time desc", array($zid));
$row = run_sql("select card_id from card where zid = ? order by time desc", array($zid));

for ($i = 0; $i < count($row); $i++) {
	//print "card_id [" . $row[$i]["card_id"] . "]";
	print_card($row[$i]["card_id"]);
}

/*
$a = array();
$a["card_id"] = 1;
$a["zid"] = "bryan@pipedot.net";
$a["time"] = time();
$a["votes"] = 1;
$a["body"] = "Pipecode is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.";
$a["story_link"] = "http://pipedot.net/story/2014-05-22/bitcoin-pizza-day";
$a["story_subject"] = "Desktops aren't dead! Lenovo PC business increases in past 12 months";
$a["story_image"] = "/pub/2014/05/31/i29.128x128.jpg";
//$a["image"] = "$server_link/pub/2014/05/31/i29.320x180.jpg";
$a["comments"] = 0;
$a["tags"] = array("pizza");

print_card_small($a);
print_card_small($a);
//print_card_small_story($a);
//print_card_medium($a);
print_card_small($a);
print_card_small($a);
print_card_small($a);
print_card_small($a);
*/

end_main("stream");

print_footer();


