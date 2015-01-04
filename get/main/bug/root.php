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
include("story.php");
include("bug.php");
include("drive.php");

if (string_uses($s2, "[A-Z][0-9]")) {
	$bug = find_rec("bug");

	print_header("Bug", array("Report"), array("ladybug"), array("/bug/report"));
	beg_main();

	writeln('<div class="bug_table">');
	print_bug($bug);
	writeln('<aside>');

	writeln('<div class="dialog_title">Priority</div>');
	writeln('<div class="dialog_body">');
	writeln('	<div class="icon_32 ' . bug_priority_icon($bug["priority"]) . '">' . ucwords($bug["priority"]) . '</div>');
	writeln('</div>');

	$row = sql("select label_name, label_tag, background_color, foreground_color from bug_labels inner join bug_label on bug_labels.label_id = bug_label.label_id where bug_short_id = ?", $bug["short_id"]);
	if (count($row) > 0) {
		writeln('<div class="dialog_title">Labels</div>');
		writeln('<div class="dialog_body">');
		for ($i = 0; $i < count($row); $i++) {
			writeln('	<div class="bug_label_wide"><a class="label" style="background-color: ' . $row[$i]["background_color"] . '; color: ' . $row[$i]["foreground_color"] . ';" href="' . $row[$i]["label_tag"] . '">' . $row[$i]["label_name"] . '</a></div>');
		}
		writeln('</div>');
	}

	$row = sql("select short_id, name, time, type from bug_file where bug_short_id = ? and (type = 'jpg' or type = 'png') order by time", $short_id);
	if (count($row) > 0) {
		writeln('<div class="dialog_title">Screenshots</div>');
		writeln('<div class="bug_screenshots">');
		for ($i = 0; $i < count($row); $i++) {
			$bug_file_short_code = crypt_crockford_encode($row[$i]["short_id"]);
			$path = public_path($row[$i]["time"]) . "/bug_file_" . $bug_file_short_code . "_256x256.jpg";
			writeln('	<a href="/pub/bug/' . $bug_file_short_code . '.' . $row[$i]["type"] . '"><img alt="screenshot" src="' . $path . '"/></a>');
		}
		writeln('</div>');
	}

	$row = sql("select short_id, name, time, type from bug_file where bug_short_id = ? and (type <> 'jpg' and type <> 'png') order by time", $short_id);
	if (count($row) > 0) {
		writeln('<div class="dialog_title">Attachments</div>');
		writeln('<div class="bug_attachments">');
		for ($i = 0; $i < count($row); $i++) {
			$bug_file_short_code = crypt_crockford_encode($row[$i]["short_id"]);
			writeln('	<div><a class="icon_16 ' . file_icon($row[$i]["type"]) . '" href="/pub/bug/' . $bug_file_short_code . '.' . $row[$i]["type"] . '">' . $row[$i]["name"] . '</a></div>');
		}
		writeln('</div>');
	}

	if ($auth_user["editor"] || $auth_user["admin"]) {
		right_box('<a class="icon_16 package_16" href="' . $bug["short_code"] . '/attachments">Manage</a> | <a class="icon_16 clip_16" href="' . $bug["short_code"] . '/attach">Attach</a>');
	} else {
		right_box('<a class="icon_16 clip_16" href="' . $bug["short_code"] . '/attach">Attach</a>');
	}

	writeln('</aside>');
	writeln('</div>');

	print_comments("bug", $bug);
} else if (string_uses($s2, "[a-z][0-9]-")) {
	$bug_label = db_get_rec("bug_label", array("label_tag" => $s2));

	print_header("Label = " . $bug_label["label_name"], array("Report"), array("ladybug"), array("/bug/report"));
	beg_main();
	writeln("<h1>Label = " . $bug_label["label_name"] . "</h1>");

	$items_per_page = 100;
	list($item_start, $page_footer) = page_footer("bug", $items_per_page, array("closed" => 0));

	$row = sql("select bug_id, author_zid, body, priority, publish_time, short_id, title from bug inner join bug_labels on bug.short_id = bug_labels.bug_short_id where label_id = ? order by publish_time desc limit $item_start, $items_per_page", $bug_label["label_id"]);
	$comments = 0;
	if ($comments == 1) {
		$comments_label = " comment";
	} else {
		$comments_label = " comments";
	}
	beg_tab();
	for ($i = 0; $i < count($row); $i++) {
		$author_zid = user_page_link($row[$i]["author_zid"], true);
		$short_code = crypt_crockford_encode($row[$i]["short_id"]);
		$icon = bug_priority_icon($row[$i]["priority"]);
		$labels = make_bug_labels($row[$i]["short_id"]);

		writeln('	<tr>');
		writeln('		<td>');
		writeln('			<div class="bug_row ' . $icon . '">');
		writeln('				<div class="bug_title"><div><a href="' . $short_code . '">' . $row[$i]["title"] . '</a></div><div>' . $labels . '</div></div>');
		writeln('				<div class="bug_subtitle">by <b>' . $author_zid . '</b> on ' . date("Y-m-d H:i", $row[$i]["publish_time"]) . ' (<a href="/' . $short_code . '">#' . $short_code . '</a>)</div>');
		writeln('			</div>');
		writeln('		</td>');
		writeln('		<td class="right"><a href="' . $short_code . '">' . $comments . $comments_label . '</a></td>');
		writeln('	</tr>');
	}
	end_tab();

} else {
	die("invalid request [$s2]");
}

end_main();
print_footer();


