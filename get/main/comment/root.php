<?
//
// Pipecode - distributed social network
// Copyright (C) 2014-2016 Bryan Beicker <bryan@pipedot.org>
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

include("diff.php");

$comment = item_request(TYPE_COMMENT);
$comment_body = $comment["body"];
$can_moderate = true;
$a = article_info($comment);
$type_id = $a["type_id"];
$article_code = crypt_crockford_encode($comment["article_id"]);

$spinner[] = ["name" => "Comment", "link" => "/comment/"];
$spinner[] = ["name" => $comment["subject"], "short" => $comment["short_code"], "link" => "/comment/" . $comment["short_code"]];

print_header();

writeln('<h1>' . ucwords(item_type($type_id)) . '</h1>');
$icon = item_icon($type_id);
writeln('<a class="icon-16 ' . $icon . '-16" href="' . $a["link"] . '">' . $a["title"] . '</a>');

writeln('<h2>' . get_text('Preview') . '</h2>');

if (!$auth_user["javascript_enabled"]) {
	beg_form("$protocol://$server_name/moderate_noscript");
	writeln('<input type="hidden" name="article_code" value="' . $article_code . '">');
}

$list = array($comment);
$c = $comment;
while ($c["parent_id"] != 0) {
	$c = db_get_rec("comment", $c["parent_id"]);
	$list[] = $c;
}

$s = "";
for ($i = count($list) - 1; $i >= 0; $i--) {
	$s .= render_comment($list[$i]["subject"], $list[$i]["zid"], $list[$i]["publish_time"], $list[$i]["comment_id"], $list[$i]["body"], 0, "", "", $list[$i]["junk_status"]);
}
$s .= str_repeat("</div>\n</article>\n", count($list));
writeln($s);

if (!$auth_user["javascript_enabled"]) {
	end_form();
}

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

		writeln('<div class="edit-title">' . date("Y-m-d H:i", $row[$i]["edit_time"]) . '</div>');
		writeln('<div class="edit-body">' . $diff . '</div>');
	}
}

$row = sql("select * from comment_vote where comment_id = ?", $comment["comment_id"]);
if (count($row) > 0) {
	writeln('<h2>' . get_text('Moderation') . '</h2>');

	beg_tab();
	writeln('	<tr>');
	writeln('		<th>' . get_text('Time') . '</th>');
	writeln('		<th>' . get_text('Reason') . '</th>');
	writeln('		<th>' . get_text('Points') . '</th>');
	writeln('		<th>' . get_text('Voter') . '</th>');
	writeln('	</tr>');
	for ($i = 0; $i < count($row); $i++) {
		$value = (int) $row[$i]["value"];
		if ($value > 0) {
			$value = "+$value";
		}
		writeln('	<tr>');
		writeln('		<td>' . date("Y-m-d H:i", $row[$i]["time"]) . '</td>');
		writeln('		<td>' . $row[$i]["reason"] . '</td>');
		writeln('		<td>' . $value . '</td>');
		writeln('		<td>' . user_link($row[$i]["zid"], ["tag" => true]) . '</td>');
		writeln('	</tr>');
	}
	end_tab();
}

writeln('<h2>' . get_text('Junk Status') . '</h2>');

if ($comment["junk_status"] == -1) {
	writeln("<p>Marked as [<b>Not Junk</b>] by " . user_link($comment["junk_zid"], ["tag" => true]) . " on " . date("Y-m-d H:i", $comment["junk_time"]) . "</p>");
} else if ($comment["junk_status"] == 0) {
	writeln("<p>Not marked as junk</p>");
} else if ($comment["junk_status"] == 1) {
	writeln("<p>Marked as [<b>Spam</b>] by " . user_link($comment["junk_zid"], ["tag" => true]) . " on " . date("Y-m-d H:i", $comment["junk_time"]) . "</p>");
}
if ($auth_user["admin"] || $auth_user["editor"]) {
	beg_form();
	writeln('<p>');
	writeln('<label><input name="junk" type="radio" value="spam"' . ($comment["junk_status"] == 1 ? ' checked' : '') . '>Spam</label>');
	writeln('<label><input name="junk" type="radio" value="not-junk"' . ($comment["junk_status"] != 1 ? ' checked' : '') . '>Not Junk</label>');
	writeln('</p>');
	box_left("Save");
	end_form();
}

print_footer();
