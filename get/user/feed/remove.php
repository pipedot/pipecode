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

require_mine();

$feed_id = http_get_int("feed_id");
$feed = db_get_rec("feed", $feed_id);

$spinner[] = ["name" => "Feed", "link" => "/feed/"];
$spinner[] = ["name" => "Remove", "link" => "/feed/remove?feed_id=$feed_id"];

print_header(["form" => true]);

writeln('<h1>' . get_text('Remove Feed') . '</h1>');
writeln('<p>' . get_text('Are you sure you want to remove [<b>$1</b>] from your page?', $feed["title"]) . '</p>');

box_left("Remove");

print_footer(["form" => true]);
