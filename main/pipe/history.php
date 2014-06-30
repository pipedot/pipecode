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

print_header("Pipe History");
beg_main();
writeln("<h1>Pipe History</h1>");

$items_per_page = 50;
list($item_start, $page_footer) = page_footer("pipe", $items_per_page);

$row = run_sql("select pipe.pid, pipe.time, pipe.title, pipe.author_zid, pipe.edit_zid, closed, reason, sid from pipe left join story on pipe.pid = story.pid order by pid desc limit $item_start, $items_per_page");
beg_tab();
writeln('	<tr>');
writeln('		<th>Date</th>');
writeln('		<th>Title</th>');
writeln('		<th>Submitter</th>');
writeln('		<th>Editor</th>');
writeln('		<th>Status</th>');
writeln('	</tr>');
for ($i = 0; $i < count($row); $i++) {
	$author = user_page_link($row[$i]["author_zid"], true);
	//if ($row[$i]["author_zid"] == "") {
	//	$author = 'Anonymous Coward';
	//} else {
	//	$author = '<a href="' . user_page_link($row[$i]["author_zid"]) . '">' . $row[$i]["author_zid"] . '</a>';
	//}
	$editor = user_page_link($row[$i]["edit_zid"], true, false);
	//if ($row[$i]["edit_zid"] == "") {
	//	$editor = '';
	//} else {
	//	$editor = '<a href="' . user_page_link($row[$i]["edit_zid"]) . '">' . $row[$i]["edit_zid"] . '</a>';
	//}
	if ($row[$i]["sid"] > 0) {
		$status = '<a href="/story/' . $row[$i]["sid"] . '">Published</a>';
	} else if ($row[$i]["closed"] == 1) {
		$status = "Closed (" . ($row[$i]["reason"] == "" ? "no reason" : $row[$i]["reason"]) . ")";
	} else {
		$status = "Voting";
	}

	writeln('	<tr>');
	writeln('		<td style="white-space: nowrap">' . date("Y-m-d H:i", $row[$i]["time"]) . '</td>');
	writeln('		<td><a href="/pipe/' . $row[$i]["pid"] . '">' . $row[$i]["title"] . '</a></td>');
	writeln('		<td>' . $author . '</td>');
	writeln('		<td>' . $editor . '</td>');
	writeln('		<td>' . $status . '</td>');
	writeln('	</tr>');
}
end_tab();

writeln($page_footer);

end_main();
print_footer();
