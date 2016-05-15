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

$spinner[] = ["name" => "Feed", "link" => "/feed/"];
$spinner[] = ["name" => "Topic", "link" => "/feed/topic/"];
$spinner[] = ["name" => "List", "link" => "/feed/topic/list"];

print_header(["title" => "Feed Topics"]);

dict_beg();
$list = db_get_list("feed_topic", "name");
foreach ($list as $topic) {
	dict_row('<a class="icon-16 ' . $topic["icon"] . '-16" href="' . $topic["slug"] . '/edit">' . $topic["name"] . '</a>', '<a class="icon-16 minus-16" href="' . $topic["slug"] . '/remove">' . get_text('Remove') . '</a>');
}
dict_end();

box_right('<a class="icon-16 plus-16" href="add">' . get_text('Add') . '</a>');

print_footer();
