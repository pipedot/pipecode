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

$spinner[] = ["name" => "Feed", "link" => "/feed/"];
$spinner[] = ["name" => "Topic", "link" => "/feed/topic/"];

print_header(["title" => "Feed Topics"]);

$list = db_get_list("feed_topic", "name");
foreach ($list as $topic) {
	writeln('<a class="topic-box ' . $topic["icon"] . '-64" href="' . $topic["slug"] . '">' . $topic["name"] . '</a>');
}

print_footer();
