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

include("feed.php");

require_mine();

$spinner[] = ["name" => "Reader", "link" => "/reader/"];
$spinner[] = ["name" => "Add", "link" => "/reader/add"];

print_header(["title" => "Add Feed", "form" => true]);

writeln('<h1>' . get_text('New Feed') . '</h1>');

beg_tab();
print_row(array("caption" => "Feed URL", "text_key" => "feed_url"));
end_tab();

box_right("Add");

writeln('<h2>' . get_text('Browse Feeds') . '</h2>');

$list = db_get_list("feed_topic", "name");
$k = array_keys($list);
for ($i = 0; $i < count($list); $i++) {
	$topic = $list[$k[$i]];
	writeln('<a class="topic-box ' . $topic["icon"] . '-64" href="' . $protocol . '://' . $server_name . '/feed/topic/' . $topic["slug"] . '">' . $topic["name"] . '</a>');
}

print_footer(["form" => true]);
