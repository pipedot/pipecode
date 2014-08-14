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
include("pipe.php");
include("story.php");

$pipe_id = $s2;
if (!string_uses($pipe_id, "[a-z][0-9]_")) {
	die("invalid pipe_id [$pipe_id]");
}

$pipe = db_get_rec("pipe", $pipe_id);
$status = "Voting";
$story_id = "";
if ($pipe["closed"]) {
	$status = "Closed";
	$row = sql("select story_id from story where pipe_id = ?", $pipe_id);
	if (count($row) > 0) {
		$story_id = $row[0]["story_id"];
		$status = "<a href=\"/story/$story_id\">Published</a>";
	}
}
if ($pipe["reason"] == "") {
	$reason = "";
} else {
	$reason = " (" . $pipe["reason"] . ")";
}

if ($auth_zid != "") {
	$can_moderate = true;
	$hide_value = $auth_user["hide_threshold"];
	$expand_value = $auth_user["expand_threshold"];
} else {
	$can_moderate = false;
	$hide_value = -1;
	$expand_value = 0;
}

print_header($pipe["title"]);

print_left_bar("main", "pipe");

beg_main("cell");
print_pipe($pipe_id);

if ($story_id > 0) {
	$list = db_get_list("story_edit", "edit_time", array("story_id" => $story_id));
	$keys = array_keys($list);
	for ($i = 0; $i < count($list); $i++) {
		$edit_time = $list[$keys[$i]]["edit_time"];
		print_story_edit($story_id, $edit_time);
	}
	print_story_edit($story_id);
}

if ($auth_user["javascript_enabled"]) {
	render_sliders("pipe", $pipe_id);
	print_noscript();
} else {
	render_page("pipe", $pipe_id, false);
}

end_main();

writeln('<aside>');
writeln('<div class="dialog_title">Status</div>');
writeln('<div class="dialog_body">');
writeln('	<div class="pipe_status">' . $status . $reason . '</div>');
writeln('</div>');

if (!$pipe["closed"]) {
	if ($auth_user["editor"]) {
		writeln('<div class="dialog_title">Editor</div>');
		writeln('<div class="dialog_body">');
		writeln('	<div class="pipe_editor"><a href="/pipe/' . $pipe_id . '/publish">Publish</a> | <a href="/pipe/' . $pipe_id . '/close">Close</a></div>');
		writeln('</div>');
	}
//} else {
//	writeln('<div class="dialog_title">Editor</div>');
//	writeln('<div class="dialog_body">');
//	writeln('	<div class="pipe_editor"><a href="' . user_page_link($pipe["editor"]) . '"><b>' . $pipe["editor"] . '</b></a></div>');
//	writeln('</div>');
}
writeln('</aside>');

$last_seen = update_view_time("pipe", $pipe_id);

if ($auth_user["javascript_enabled"]) {
	writeln('<script>');
	writeln();
	writeln('var hide_value = ' . $hide_value . ';');
	writeln('var expand_value = ' . $expand_value . ';');
	writeln('var auth_zid = "' . $auth_zid . '";');
	writeln('var last_seen = ' . $last_seen . ';');
	writeln();
	writeln('get_comments("pipe", "' . $pipe_id . '");');
	writeln('render_page();');
	writeln();
	writeln('</script>');
}

print_footer();
