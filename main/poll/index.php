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

print_header("Polls");

writeln('<table class="fill">');
writeln('<tr>');
writeln('<td class="left_col">');
print_left_bar("main", "poll");
writeln('</td>');
writeln('<td class="fill">');

writeln('<h1>Polls</h1>');

writeln('<table class="zebra">');
$row = run_sql("select qid, question, time from poll_question order by qid desc");
for ($i = 0; $i < count($row); $i++) {
	writeln('	<tr>');
	writeln('		<td><a href="' . $row[$i]["qid"] . '">' . $row[$i]["question"] . '</a></td>');
	writeln('		<td style="text-align: right">' . date("Y-m-d", $row[$i]["time"]) . '</td>');
	writeln('	</tr>');
}
writeln('</table>');

writeln('		</td>');
writeln('	</tr>');
writeln('</table>');

print_footer();
