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

print_header("Pipe History");
beg_main();
writeln("<h1>Pipe History</h1>");

$items_per_page = 100;
list($item_start, $page_footer) = page_footer("pipe", $items_per_page);

$row = sql("select pipe.pipe_id, pipe.time, pipe.title, pipe.author_zid, pipe.edit_zid, closed, reason, story_id from pipe left join story on pipe.pipe_id = story.pipe_id order by time desc limit $item_start, $items_per_page");
beg_tab();
writeln('	<tr>');
writeln('		<th>Date</th>');
writeln('		<th>Title</th>');
writeln('		<th>Submitter</th>');
writeln('		<th>Editor</th>');
writeln('		<th>Status</th>');
writeln('	</tr>');
for ($i = 0; $i < count($row); $i++) {
	$pipe_code = crypt_crockford_encode($row[$i]["pipe_id"]);
	$story_code = crypt_crockford_encode($row[$i]["story_id"]);
	$author = user_link($row[$i]["author_zid"], ["tag" => true]);
	$editor = user_link($row[$i]["edit_zid"], ["tag" => true, "ac" => false]);
	if ($story_code > 0) {
		$status = '<a href="/story/' . $story_code . '">Published</a>';
	} else if ($row[$i]["closed"] == 1) {
		$status = "Closed (" . ($row[$i]["reason"] == "" ? "no reason" : $row[$i]["reason"]) . ")";
	} else {
		$status = "Voting";
	}

	writeln('	<tr>');
	writeln('		<td style="white-space: nowrap">' . date("Y-m-d H:i", $row[$i]["time"]) . '</td>');
	writeln('		<td><a href="/pipe/' . $pipe_code . '">' . $row[$i]["title"] . '</a></td>');
	writeln('		<td>' . $author . '</td>');
	writeln('		<td>' . $editor . '</td>');
	writeln('		<td>' . $status . '</td>');
	writeln('	</tr>');
}
end_tab();

writeln($page_footer);

end_main();
print_footer();
