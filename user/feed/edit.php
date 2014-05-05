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

writeln('<table class="fill">');
writeln('<tr>');
writeln('<td class="left_col">');
print_left_bar("account", "feed");
writeln('</td>');
writeln('<td class="fill">');

writeln('<table class="fill">');
writeln('	<tr>');

for ($c = 0; $c < 3; $c++) {
	writeln('		<td class="feed_box">');
	writeln('			<table class="zebra">');
	$r = 0;
	$row = run_sql("select feed_user.fid, title from feed_user inner join feed on feed_user.fid = feed.fid where zid = ? and col = ? order by pos", array($auth_zid, $c));
	if (count($row) == 0) {
		writeln('				<tr><td>(no feeds)</td></tr>');
	}
	for ($i = 0; $i < count($row); $i++) {
		writeln('				<tr>');
		writeln('					<td>' . $row[$i]["title"] . '</td>');
		writeln('					<td class="right"><a href="remove?fid=' . $row[$i]["fid"] . '"><span class="icon_16" style="background-image: url(/images/remove-16.png)">Remove</span></a></td>');
		writeln('				</tr>');
		$r = ($r ? 0 : 1);
	}
	writeln('			</table>');
	writeln('			<div class="right"><a href="add?col=' . $c . '"><span class="icon_16" style="background-image: url(/images/add-16.png)">Add</span></a></div>');
	writeln('		</td>');
}
writeln('	</tr>');
writeln('</table>');

writeln('</td>');
writeln('</tr>');
writeln('</table>');

print_footer();
