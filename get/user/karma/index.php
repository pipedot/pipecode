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

$row = sql("select sum(value) as karma from comment inner join comment_vote on comment.comment_id = comment_vote.comment_id where comment.zid = ?", $zid);
$karma = (int) $row[0]["karma"];
$description = karma_description($karma);
$icon = karma_icon($karma);

$page = http_get_int("page", array("default" => 1, "required" => false));
$items_per_page = 100;
list($item_start, $page_footer) = page_footer("select count(*) as item_count from comment inner join comment_vote on comment.comment_id = comment_vote.comment_id where comment.zid = ? and value <> 0", $items_per_page, $zid);

print_header("Karma", [], [], [], ["Karma"], ["/karma/"]);
beg_main();

writeln('<h1>' . get_text("Current") . '</h1>');
writeln('<div class="icon-32 ' . $icon . '-32">' . get_text($description) . ' (' . $karma . ')</div>');

$row = sql("select comment_vote.time, value, comment.comment_id, comment_vote.zid from comment inner join comment_vote on comment.comment_id = comment_vote.comment_id where comment.zid = ? and value <> 0 order by comment_vote.time desc limit $item_start, $items_per_page", $zid);
writeln('<h2>' . get_text("Log") . '</h2>');
writeln('<table class="zebra">');
writeln('	<tr>');
writeln('		<th>' . get_text("Time") . '</th>');
writeln('		<th class="center">' . get_text("Points") . '</th>');
writeln('		<th>' . get_text("Comment") . '</th>');
writeln('		<th>' . get_text("Voter") . '</th>');
writeln('	</tr>');
if (count($row) == 0) {
	writeln('	<tr>');
	writeln('		<td colspan="3">(' . get_text("none") . ')</td>');
	writeln('	</tr>');
}
for ($i = 0; $i < count($row); $i++) {
	$comment_code = crypt_crockford_encode($row[$i]["comment_id"]);
	$link = item_link(TYPE_COMMENT, $row[$i]["comment_id"]);
	$value = (int) $row[$i]["value"];
	if ($value > 0) {
		$value = "+$value";
	}
	$voter = user_link($row[$i]["zid"], ["tag" => true]);
	writeln('	<tr>');
	writeln('		<td>' . gmdate("Y-m-d H:i", $row[$i]["time"]) . '</td>');
	writeln('		<td class="center">' . $value . '</td>');
	writeln('		<td><a href="' . $protocol . '://' . $server_name . '/' . $comment_code . '">#' . $comment_code . '</a></td>');
	writeln('		<td>' . $voter . '</td>');
	writeln('	</tr>');
}
end_tab();

writeln($page_footer);

end_main();
print_footer();
