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

print_header("Reader");
print_main_nav("reader");
beg_main("cell");

writeln('<h1>' . get_text('Reader') . '</h1>');

$list = db_get_list("reader_topic", "name");
$k = array_keys($list);
for ($i = 0; $i < count($list); $i++) {
	$topic = $list[$k[$i]];
	writeln('<a class="topic-box ' . $topic["icon"] . '-64" href="/reader/' . $topic["slug"] . '">' . $topic["name"] . '</a>');
}

if ($auth_user["admin"]) {
	box_left('<a class="icon-16 plus-16" href="edit">' . get_text('Add') . '</a>');
}

end_main();
print_footer();

