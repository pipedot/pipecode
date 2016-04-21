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

require_mine();

$feed = item_request(TYPE_READER);

$topic_keys = [0];
$topic_names = ["(none)"];
$list = db_get_list("reader_topic", "name", ["zid" => $auth_zid]);
$k = array_keys($list);
for ($i = 0; $i < count($list); $i++) {
	$topic = $list[$k[$i]];
	$topic_keys[] = $topic["topic_id"];
	$topic_names[] = $topic["name"];
}

$spinner[] = ["name" => "Reader", "link" => "/reader/"];
$spinner[] = ["name" => $feed["name"], "link" => "/reader/" . $feed["slug"]];
$spinner[] = ["name" => "Edit", "link" => "/reader/" . $feed["slug"] . "/edit"];

print_header(["title" => "Edit Feed", "form" => true]);

beg_tab();
print_row(array("caption" => "Name", "text_key" => "name", "text_value" => $feed["name"]));
print_row(array("caption" => "Slug", "text_key" => "slug", "text_value" => $feed["slug"]));
print_row(array("caption" => "Topic", "option_key" => "topic_id", "option_keys" => $topic_keys, "option_list" => $topic_names, "option_value" => $feed["topic_id"]));
end_tab();

box_right("Save");

print_footer();
