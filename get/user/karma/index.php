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

$page = http_get_int("page", array("default" => 1, "required" => false));
switch ($description) {
	case "Excellent":
		$icon = "face-grin";
		break;
	case "Good":
		$icon = "face-smile";
		break;
	case "Neutral":
		$icon = "face-plain";
		break;
	case "Bad":
		$icon = "face-sad";
		break;
	case "Terrible":
		$icon = "face-crying";
		break;
}
//$rows_per_page = 10;
//$row = sql("select count(zid) as row_count from karma_log where zid = ?", $zid);
//$row = sql("select count(*) as row_count from comment inner join comment_vote on comment.comment_id = comment_vote.comment_id where comment.zid = ? and value <> 0", $zid);
//$row_count = (int) $row[0]["row_count"];
//$pages_count = ceil($row_count / $rows_per_page);
//$row_start = ($page - 1) * $rows_per_page;
$items_per_page = 100;
list($item_start, $page_footer) = page_footer("select count(*) as item_count from comment inner join comment_vote on comment.comment_id = comment_vote.comment_id where comment.zid = ? and value <> 0", $items_per_page, $zid);

print_header("Karma");
print_user_nav("karma");
beg_main("cell");

writeln('<h1>Karma</h1>');
writeln('<div class="icon-32 ' . $icon . '-32">' . $description . ' (' . $karma . ')</div>');

//writeln('<table>');
//writeln('	<tr>');
//writeln('		<td><img alt="Karma Face" src="/images/' . $icon . '"/></td>');
//writeln('		<td>' . $description . ' (' . $karma . ')</td>');
//writeln('	</tr>');
//writeln('</table>');

//$row = sql("select time, karma_log.value, karma_log.type_id, type, id from karma_log inner join karma_type on karma_log.type_id = karma_type.type_id where zid = ? order by time desc limit $row_start, $rows_per_page", $zid);
$row = sql("select comment_vote.time, value, comment.comment_id, comment_vote.zid from comment inner join comment_vote on comment.comment_id = comment_vote.comment_id where comment.zid = ? and value <> 0 order by comment_vote.time desc limit $item_start, $items_per_page", $zid);
writeln('<h2>Log</h2>');
writeln('<table class="zebra">');
writeln('	<tr>');
writeln('		<th>Time</th>');
writeln('		<th class="center">Points</th>');
writeln('		<th>Comment</th>');
writeln('		<th>Voter</th>');
writeln('	</tr>');
if (count($row) == 0) {
	writeln('	<tr>');
	writeln('		<td colspan="3">(none)</td>');
	writeln('	</tr>');
}
for ($i = 0; $i < count($row); $i++) {
	//if ($row[$i]["type_id"] == 1 || $row[$i]["type_id"] == 2) {
	//	$link = " (<a href=\"http://$server_name/comment/" . $row[$i]["id"] . '">#' . $row[$i]["comment_id"] . '</a>';
	//} else {
	//	$link = " (<a href=\"http://$server_name/pipe/" . $row[$i]["id"] . '">#' . $row[$i]["comment_id"] . '</a>';
	//}
	$comment_code = crypt_crockford_encode($row[$i]["comment_id"]);
	$link = item_link("comment", $row[$i]["comment_id"]);
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

//$s = "";
//for ($i = 1; $i <= $pages_count; $i++) {
//	if ($i == $page) {
//		$s .= "$i ";
//	} else {
//		$s .= "<a href=\"?page=$i\">$i</a> ";
//	}
//}
//writeln('<div style="text-align: center">' . trim($s) . '</div>');

writeln($page_footer);

end_main();
print_footer();
