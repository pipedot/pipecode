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

require_feature("twitter");
require_editor();

$story = item_request(TYPE_STORY);
$topic = db_get_rec("topic", $story["topic_id"]);
$topic = $topic["topic"];
if ($story["tweet_id"] > 0) {
	fatal("Already tweeted");
}
$day = gmdate("Y-m-d", $story["publish_time"]);
$slug = $story["slug"];

print_header();
print_main_nav("stories");
beg_main("cell");
beg_form();
writeln('<h1>Send Tweet</h1>');

$status_text = $story["title"] . " https://$server_name/{$story["short_code"]} #$topic";
beg_tab();
print_row(array("caption" => "Status Text", "text_key" => "status_text", "text_value" => $status_text));
end_tab();

box_right("Send");

end_form();
end_main();
print_footer();
