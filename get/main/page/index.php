<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
//
// This file is part of Pipecode.
//
// Pipecode is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Pipecode is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Pipecode.  If not, see <http://www.gnu.org/licenses/>.
//

if (!$auth_user["admin"]) {
	die("not an admin");
}

print_header("Pages");
beg_main();
writeln('<h1>Pages</h1>');

beg_tab();
//writeln('	<tr>');
//writeln('		<th>View</th>');
//writeln('		<th>Edit</th>');
//writeln('		<th>Title</th>');
//writeln('		<th></th>');
//writeln('	</tr>');
$list = db_get_list("page", "slug");
$keys = array_keys($list);
if (count($keys) == 0) {
	writeln('	<tr>');
	writeln('		<td>(no pages)</td>');
	writeln('	</tr>');
}
for ($i = 0; $i < count($keys); $i++) {
	$page = $list[$keys[$i]];
	writeln('	<tr>');
	writeln('		<td><a href="edit?slug=' . $page["slug"] . '" class="icon_notepad_16">' . $page["title"] . '</a></td>');
	writeln('		<td class="right"><a href="remove?slug=' . $page["slug"] . '" class="icon_minus_16">Remove</a></td>');
	writeln('	</tr>');
}
end_tab();

right_box('<a href="add" class="icon_plus_16">Add</a>');

end_main();
print_footer();
