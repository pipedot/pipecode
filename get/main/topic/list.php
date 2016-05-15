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

$spinner[] = ["name" => "Topic", "link" => "/topic/"];
$spinner[] = ["name" => "List", "link" => "/topic/list"];

print_header(["title" => "Topics"]);

beg_tab();
$list = db_get_list("topic", "topic");
foreach ($list as $topic) {
	writeln('	<tr>');
	writeln('		<td class="hover">');
	writeln('			<a href="/topic/' . $topic["slug"] . '/edit">');
	writeln('			<dl class="dl-32 ' . $topic["icon"] . '-32">');
	writeln('				<dt>' . $topic["topic"] . '</dt>');
	writeln('				<dd>' . $server_name . '</dd>');
	writeln('			</dl>');
	writeln('			</a>');
	writeln('		</td>');
	writeln('	</tr>');
}
end_tab();

box_right('<a class="icon-16 plus-16" href="edit">' . get_text('Add') . '</a>');

print_footer();
