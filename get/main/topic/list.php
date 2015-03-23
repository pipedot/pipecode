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
	die("not an admin");
}

print_header("Topics");
//print_left_bar("main", "topics");
//beg_main("cell");
beg_main();

writeln('<h1>Topics</h1>');

$list = db_get_list("topic", "topic");
$k = array_keys($list);
for ($i = 0; $i < count($list); $i++) {
	$topic = $list[$k[$i]];
	//writeln('<a href="/topic/' . $topic["slug"] . '"><div class="topic-box ' . $topic["icon"] . '-64">' . $topic["topic"] . '</div></a>');
	writeln('<a class="topic-box ' . $topic["icon"] . '-64" href="/topic/' . $topic["slug"] . '/edit">' . $topic["topic"] . '</a>');
}

/*
$list = db_get_list("topic", "topic");
$k = array_keys($list);
beg_tab();
for ($i = 0; $i < count($list); $i++) {
	$topic = $list[$k[$i]];
	writeln('	<tr>');
	writeln('		<td><a class="icon-64 ' . $topic["icon"] . '-64" href="' . $topic["topic"] . '/edit">' . $topic["topic"] . '</a></td><td class="right"><a class="icon-16 minus-16" href="">Remove</a></td>');
	writeln('	</tr>');
}
end_tab();
*/
box_left('<a class="icon-16 plus-16" href="edit">Add</a>');

end_main();
print_footer();
