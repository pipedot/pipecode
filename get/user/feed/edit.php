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

require_mine();

print_header("Edit Feed", [], [], [], ["Feed", "Edit"], ["/feed/", "/feed/edit"]);
beg_main();

writeln('<table class="fill">');
writeln('	<tr>');

for ($c = 0; $c < 3; $c++) {
	writeln('		<td class="feed-box">');
	writeln('			<table class="zebra">');
	$row = sql("select feed_user.feed_id, title from feed_user inner join feed on feed_user.feed_id = feed.feed_id where zid = ? and col = ? order by pos", $auth_zid, $c);
	if (count($row) == 0) {
		writeln('				<tr><td>' . get_text('(none)') . '</td></tr>');
	}
	for ($i = 0; $i < count($row); $i++) {
		writeln('				<tr>');
		writeln('					<td>' . $row[$i]["title"] . '</td>');
		writeln('					<td class="right"><a href="remove?feed_id=' . $row[$i]["feed_id"] . '" class="icon-16 minus-16">' . get_text('Remove') . '</a></td>');
		writeln('				</tr>');
	}
	writeln('			</table>');
	writeln('			<div class="right"><a href="add?col=' . $c . '" class="icon-16 plus-16">' . get_text('Add') . '</a></div>');
	writeln('		</td>');
}
writeln('	</tr>');
writeln('</table>');

end_main();
print_footer();
