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

if (!@$auth_user["editor"]) {
	die("you are not an editor");
}

$story_id = $s2;
if (!string_uses($story_id, "[a-z][0-9]_")) {
	die("invalid story_id [$story_id]");
}
$story = db_get_rec("story", $story_id);
$topic = db_get_rec("topic", $story["tid"]);
$topic = $topic["topic"];
if ($story["tweet_id"] > 0) {
	die("already tweeted");
}
$day = gmdate("Y-m-d", $story["publish_time"]);
$slug = $story["slug"];

print_header();
print_left_bar("main", "stories");
beg_main("cell");
beg_form();
writeln('<h1>Send Tweet</h1>');

$status_text = $story["title"] . " https://$server_name/" . crypt_crockford_encode($story["short_id"]) . " #$topic";
beg_tab();
print_row(array("caption" => "Status Text", "text_key" => "status_text", "text_value" => $status_text));
end_tab();

right_box("Send");

end_form();
end_main();
print_footer();
