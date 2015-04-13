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

if (!$auth_user["admin"]) {
	die("you are not an admin");
}

print_header("Feed Topics");
beg_main();
beg_form();

writeln('<h1>Topics</h1>');

dict_beg();
$list = db_get_list("feed_topic", "name");
$k = array_keys($list);
for ($i = 0; $i < count($list); $i++) {
	$topic = $list[$k[$i]];
	dict_row('<a class="icon-16 ' . $topic["icon"] . '-16" href="' . $topic["slug"] . '/edit">' . $topic["name"] . '</a>', '<a class="icon-16 minus-16" href="' . $topic["slug"] . '/delete">Delete</a>');

}
dict_end();

box_right('<a class="icon-16 plus-16" href="add">Add</a>');

end_form();
end_main();
print_footer();

