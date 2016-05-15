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

$spinner[] = ["name" => "Page", "link" => "/page/"];

print_header(["title" => "Pages"]);

writeln('<h1>' . get_text('Pages') . '</h1>');

dict_beg();
$list = db_get_list("page", "slug");
$keys = array_keys($list);
if (count($keys) == 0) {
	dict_none();
}
for ($i = 0; $i < count($keys); $i++) {
	$page = $list[$keys[$i]];

	dict_row('<a href="' . $page["slug"] . '/edit" class="icon-16 notepad-16">' . $page["title"] . '</a>', '<a href="' . $page["slug"] . '/remove" class="icon-16 minus-16">' . get_text('Remove') . '</a>');
}
dict_end();

box_right('<a class="icon-16 plus-16" href="add">' . get_text('Add') . '</a>');

print_footer();
