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
	writeln('		<td style="width: 30%"><a href="/' . $page["slug"] . '" class="icon_16" style="background-image: url(/images/html-16.png)">/' . $page["slug"] . '</a></td>');
	writeln('		<td style="width: 50%">' . $page["title"] . '</td>');
	writeln('		<td style="width: 10%"><a href="edit?slug=' . $page["slug"] . '" class="icon_16" style="background-image: url(/images/notepad-16.png)">Edit</a></td>');
	writeln('		<td style="width: 10%" class="right"><a href="remove?slug=' . $page["slug"] . '" class="icon_16" style="background-image: url(/images/remove-16.png)">Remove</a></td>');
	writeln('	</tr>');
}
end_tab();

writeln('<div class="right"><a href="add" class="icon_16" style="background-image: url(/images/add-16.png)">Add</a></div>');

print_footer();
