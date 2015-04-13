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

if ($auth_zid === "") {
	die("sign in to add");
}

$feed = item_request("feed");

if (db_has_rec("reader_user", ["zid" => $auth_zid, "feed_id" => $feed["feed_id"]])) {
	die("feed already added");
}

$feed_topic = db_get_rec("feed_topic", $feed["topic_id"]);
$topic_names = ["(no topic)"];
$topic_keys = [0];
$topic_new = true;
$topic_value = 0;
$list = db_get_list("reader_topic", "name", ["zid" => $auth_zid]);
$k = array_keys($list);
for ($i = 0; $i < count($list); $i++) {
	$reader_topic = $list[$k[$i]];
	if ($reader_topic["name"] == $feed_topic["name"] || $reader_topic["slug"] == $feed_topic["slug"]) {
		$topic_new = false;
		$topic_value = $reader_topic["topic_id"];
	}
	$topic_names[] = $reader_topic["name"];
	$topic_keys[] = $reader_topic["topic_id"];
}
if ($topic_new) {
	$topic_names[] = $feed_topic["name"];
	$topic_keys[] = -1;
	$topic_value = -1;
}

print_header("Add Feed");
beg_main();
beg_form();
writeln('<h1>Add Feed</h1>');

beg_tab();
print_row(array("caption" => "Name", "text_key" => "name", "text_value" => $feed["title"]));
print_row(array("caption" => "Slug", "text_key" => "slug", "text_value" => $feed["slug"]));
print_row(array("caption" => "Topic", "option_key" => "topic_id", "option_keys" => $topic_keys, "option_list" => $topic_names, "option_value" => $topic_value));
end_tab();

box_right("Add");

end_form();
end_main();
print_footer();

