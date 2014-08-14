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

if ($zid != $auth_zid) {
	die("not your page");
}

print_header();
print_left_bar("user", "feed");
beg_main("cell");

writeln('<table class="fill">');
writeln('	<tr>');

for ($c = 0; $c < 3; $c++) {
	writeln('		<td class="feed_box">');
	writeln('			<table class="zebra">');
	$r = 0;
	$row = sql("select feed_user.fid, title from feed_user inner join feed on feed_user.fid = feed.fid where zid = ? and col = ? order by pos", $auth_zid, $c);
	if (count($row) == 0) {
		writeln('				<tr><td>(no feeds)</td></tr>');
	}
	for ($i = 0; $i < count($row); $i++) {
		writeln('				<tr>');
		writeln('					<td>' . $row[$i]["title"] . '</td>');
		writeln('					<td class="right"><a href="remove?fid=' . $row[$i]["fid"] . '" class="icon_minus_16">Remove</a></td>');
		writeln('				</tr>');
		$r = ($r ? 0 : 1);
	}
	writeln('			</table>');
	writeln('			<div class="right"><a href="add?col=' . $c . '" class="icon_plus_16">Add</a></div>');
	writeln('		</td>');
}
writeln('	</tr>');
writeln('</table>');

end_main();
print_footer();
