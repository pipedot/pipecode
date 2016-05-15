<?
//
// Pipecode - distributed social network
// Copyright (C) 2014-2016 Bryan Beicker <bryan@pipedot.org>
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

require_admin();

$feed = item_request(TYPE_FEED);
$feed_code = crypt_crockford_encode($feed["feed_id"]);

$spinner[] = ["name" => "Feed", "link" => "/feed/"];
$spinner[] = ["name" => $feed["title"], "short" => $feed["slug"], "link" => "/feed/$feed_code"];
$spinner[] = ["name" => "Edit", "link" => "/feed/$feed_code/edit"];

print_header(["title" => "Edit Feed", "form" => true]);

writeln('<h1>' . get_text('Edit Feed') . '</h1>');

dict_beg();
dict_row("Title", $feed["title"]);
dict_row("Link", '<a href="' . $feed["link"] . '">' . $feed["link"] . '</a>');
dict_row("Feed", '<a href="' . $feed["uri"] . '">' . $feed["uri"] . '</a>');
if ($feed["copyright"] != "") {
	dict_row("Copyright", $feed["copyright"]);
}
dict_row("Updated", date("Y-m-d H:i", $feed["time"]));
dict_end();

$topic_keys = [0];
$topic_names = ["(none)"];

$list = db_get_list("feed_topic", "name");
$k = array_keys($list);
for ($i = 0; $i < count($list); $i++) {
	$topic = $list[$k[$i]];
	$topic_keys[] = $topic["topic_id"];
	$topic_names[] = $topic["name"];
}

beg_tab();
print_row(array("caption" => "Slug", "text_key" => "slug", "text_value" => $feed["slug"]));
print_row(array("caption" => "Topic", "option_key" => "topic_id", "option_keys" => $topic_keys, "option_list" => $topic_names, "option_value" => $feed["topic_id"]));
end_tab();

box_right("Delete,Save");

print_footer(["form" => true]);
