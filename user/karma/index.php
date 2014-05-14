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

$page = http_get_int("page", array("default" => 1, "required" => false));
$description = karma_description($user_conf["karma"]);
$icon = "karma-" . strtolower($description) . "-32.png";
$rows_per_page = 10;
$row = run_sql("select count(zid) as row_count from karma_log where zid = ?", array($zid));
$row_count = (int) $row[0]["row_count"];
$pages_count = ceil($row_count / $rows_per_page);
$row_start = ($page - 1) * $rows_per_page;

print_header("Karma");

writeln('<table class="fill">');
writeln('<tr>');
writeln('<td class="left_col">');
print_left_bar("user", "karma");
writeln('</td>');
writeln('<td class="fill">');

writeln('<h1>Karma</h1>');
writeln('<table>');
writeln('	<tr>');
writeln('		<td><img alt="Karma Face" src="/images/' . $icon . '"/></td>');
writeln('		<td>' . $description . ' (' . $user_conf["karma"] . ')</td>');
writeln('	</tr>');
writeln('</table>');

$row = run_sql("select time, karma_log.value, karma_log.type_id, type, id from karma_log inner join karma_type on karma_log.type_id = karma_type.type_id where zid = ? order by time desc limit $row_start, $rows_per_page", array($zid));
writeln('<h1>Log</h1>');
writeln('<table class="zebra">');
writeln('	<tr>');
writeln('		<th>Time</th>');
writeln('		<th class="center">Points</th>');
writeln('		<th>Action</th>');
writeln('	</tr>');
if (count($row) == 0) {
	writeln('	<tr>');
	writeln('		<td colspan="3">(none)</td>');
	writeln('	</tr>');
}
for ($i = 0; $i < count($row); $i++) {
	if ($row[$i]["type_id"] == 1 || $row[$i]["type_id"] == 2) {
		$link = " (<a href=\"http://$server_name/comment/" . $row[$i]["id"] . '">#' . $row[$i]["id"] . '</a>';
	} else {
		$link = " (<a href=\"http://$server_name/pipe/" . $row[$i]["id"] . '">#' . $row[$i]["id"] . '</a>';
	}
	$value = (int) $row[$i]["value"];
	if ($value > 0) {
		$value = "+$value";
	}
	writeln('	<tr>');
	writeln('		<td>' . gmdate("Y-m-d H:i", $row[$i]["time"]) . '</td>');
	writeln('		<td class="center">' . $row[$i]["value"] . '</td>');
	writeln('		<td>' . $row[$i]["type"] . $link . '</td>');
	writeln('	</tr>');
}
end_tab();

$s = "";
for ($i = 1; $i <= $pages_count; $i++) {
	if ($i == $page) {
		$s .= "$i ";
	} else {
		$s .= "<a href=\"?page=$i\">$i</a> ";
	}
}
writeln('<div style="text-align: center">' . trim($s) . '</div>');

writeln('</td>');
writeln('</tr>');
writeln('</table>');

print_footer();
