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

require_admin();

print_header("Footer Links");
beg_main();
writeln('<h1>' . get_text('Footer Links') . '</h1>');

dict_beg();
$row = sql("select title, icon, link from footer_link order by title");
for ($i = 0; $i < count($row); $i++) {
	$icon = $row[$i]["icon"];
	if (!$icon) {
		$icon = "globe";
	}
	dict_row('<a class="icon-16 ' . $icon . '-16" href="edit?title=' . urlencode($row[$i]["title"]) . '">' . $row[$i]["title"] . '</a>', '<a class="icon-16 minus-16" href="remove?title=' . urlencode($row[$i]["title"]) . '">Remove</a>');
}
dict_end();
box_right('<a class="icon-16 plus-16" href="add">Add</a>');

end_main();
print_footer();

