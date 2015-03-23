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

if ($auth_zid === $zid) {
	print_header("Stream", array("Share"), array("share"), array("/stream/share"));
} else {
	print_header("Stream");
}

beg_main("stream");

$row = sql("select card_id from card where zid = ? order by edit_time desc", $zid);

for ($i = 0; $i < count($row); $i++) {
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


