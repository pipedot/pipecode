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
include("$doc_root/lib/finediff/finediff.php");

if (string_uses($s2, "[A-Z][a-z][0-9]")) {
	$short_id = crypt_crockford_decode($s2);
	$short = db_get_rec("short", $short_id);
	if ($short["type"] != "comment") {
		die("invalid short code [$s2]");
	}
	$comment_id = $short["item_id"];
} else if (string_uses($s2, "[a-z][0-9]_")) {
	$comment_id = $s2;
} else {
	die("invalid request");
}

$comment = db_get_rec("comment", $comment_id);
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

$row = sql("select * from comment_edit where comment_id = ? order by edit_time", $comment_id);
if (count($row) > 0) {
	writeln('<h2>History</h2>');
	for ($i = 0; $i < count($row); $i++) {
		//$old_body = $row[$i]["body"];
		$old_body = mb_convert_encoding($row[$i]["body"], 'HTML-ENTITIES', 'UTF-8');
		if ($i == count($row) - 1) {
			//$new_body = $comment_body;
			$new_body = mb_convert_encoding($comment_body, 'HTML-ENTITIES', 'UTF-8');
		} else {
			//$new_body = $row[$i + 1]["body"];
			$new_body = mb_convert_encoding($row[$i + 1]["body"], 'HTML-ENTITIES', 'UTF-8');
		}
		$opcodes = FineDiff::getDiffOpcodes($old_body, $new_body);
		$diff = FineDiff::renderDiffToHTMLFromOpcodes($old_body, $opcodes);
		$diff = mb_convert_encoding($diff, 'UTF-8', 'HTML-ENTITIES');
		$diff = str_replace("&lt;", "<", $diff);
		$diff = str_replace("&gt;", ">", $diff);

		writeln('<div class="edit_title">' . date("Y-m-d H:i", $row[$i]["edit_time"]) . '</div>');
		writeln('<div class="edit_body">' . $diff . '</div>');
	}
}

end_main();
print_footer();

