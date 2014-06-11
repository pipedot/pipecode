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

include("render.php");

$page = http_get_int("page", array("default" => 1, "required" => false));
$rows_per_page = 10;
$row = run_sql("select count(*) as row_count from comment where zid = ?", array($zid));
$row_count = (int) $row[0]["row_count"];
$pages_count = ceil($row_count / $rows_per_page);
$row_start = ($page - 1) * $rows_per_page;

print_header("Comments");
print_left_bar("user", "comments");
beg_main("cell");

writeln('<h1>Comments</h1>');

$row = run_sql("select cid, subject, time, comment from comment where zid = ? order by time desc limit $row_start, $rows_per_page", array($zid));
for ($i = 0; $i < count($row); $i++) {
	print render_comment($row[$i]["subject"], $zid, $row[$i]["time"], $row[$i]["cid"], $row[$i]["comment"]);
	writeln('</div>');
	writeln('</article>');
	writeln();
}

$s = "";
for ($i = 1; $i <= $pages_count; $i++) {
	if ($i == $page) {
		$s .= "$i ";
	} else {
		$s .= "<a href=\"?page=$i\">$i</a> ";
	}
}
writeln('<div style="text-align: center">' . trim($s) . '</div>');

end_main();
print_footer();
