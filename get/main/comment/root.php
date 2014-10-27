<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
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

include("render.php");
include("diff.php");

$comment = find_rec("comment");
$comment_body = $comment["body"];
$can_moderate = false;
$a = article_info($comment);
$type = $a["type"];

print_header($comment["subject"]);

print_left_bar("main", "stories");
beg_main("cell");

writeln('<h1>' . ucwords($type) . '</h1>');
if ($type == "poll") {
	$icon = "heart";
} else if ($type == "journal") {
	$icon = "notepad";
} else {
	$icon = "news";
}
writeln('<a class="icon_16 ' . $icon . '_16" href="' . $a["link"] . '">' . $a["title"] . '</a>');

writeln('<h2>Preview</h2>');

$list = array($comment);
while ($comment["parent_id"] != "") {
	$comment = db_get_rec("comment", $comment["parent_id"]);
	$list[] = $comment;
}

$s = "";
for ($i = count($list) - 1; $i >= 0; $i--) {
	$s .= render_comment($list[$i]["subject"], $list[$i]["zid"], $list[$i]["publish_time"], $list[$i]["comment_id"], $list[$i]["body"], 0, $list[$i]["short_id"]);
}
$s .= str_repeat("</div>\n</article>\n", count($list));
writeln($s);

$row = sql("select * from comment_edit where comment_id = ? order by edit_time", $comment["comment_id"]);
if (count($row) > 0) {
	writeln('<h2>History</h2>');
	for ($i = 0; $i < count($row); $i++) {
		$old_body = $row[$i]["body"];
		if ($i == count($row) - 1) {
			$new_body = $comment_body;
		} else {
			$new_body = $row[$i + 1]["body"];
		}
		$diff = diff($old_body, $new_body);

		writeln('<div class="edit_title">' . date("Y-m-d H:i", $row[$i]["edit_time"]) . '</div>');
		writeln('<div class="edit_body">' . $diff . '</div>');
	}
}

end_main();
print_footer();

