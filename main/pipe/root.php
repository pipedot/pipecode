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

$pid = $s2;
if (!string_uses($pid, "[0-9]")) {
	die("invalid pid [$pid]");
}

$pipe = db_get_rec("pipe", $pid);
$status = "Voting";
$sid = 0;
if ($pipe["closed"]) {
	$status = "Closed";
	$row = run_sql("select sid from story where pid = ?", array($pid));
	if (count($row) > 0) {
		$sid = $row[0]["sid"];
		$status = "<a href=\"/story/$sid\">Published</a>";
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
print_pipe($pid);

if ($sid > 0) {
	$list = db_get_list("story_edit", "edit_time", array("sid" => $sid));
	$keys = array_keys($list);
	for ($i = 0; $i < count($list); $i++) {
		$edit_time = $list[$keys[$i]]["edit_time"];
		print_story_edit($sid, $edit_time);
	}
	print_story_edit($sid);
}

if ($auth_user["javascript_enabled"]) {
	render_sliders(0, $pid, 0);
	print_noscript();
} else {
	render_page(0, $pid, 0, false);
}

end_main();

writeln('<aside>');
//writeln('<div class="right_bar">');
writeln('<div class="dialog_title">Status</div>');
writeln('<div class="dialog_body">');
writeln('	<div class="pipe_status">' . $status . $reason . '</div>');
writeln('</div>');

if (!$pipe["closed"]) {
	if ($auth_user["editor"]) {
		writeln('<div class="dialog_title">Editor</div>');
		writeln('<div class="dialog_body">');
		writeln('	<div class="pipe_editor"><a href="/pipe/' . $pid . '/publish">Publish</a> | <a href="/pipe/' . $pid . '/close">Close</a></div>');
		writeln('</div>');
	}
//} else {
//	writeln('<div class="dialog_title">Editor</div>');
//	writeln('<div class="dialog_body">');
//	writeln('	<div class="pipe_editor"><a href="' . user_page_link($pipe["editor"]) . '"><b>' . $pipe["editor"] . '</b></a></div>');
//	writeln('</div>');
}
writeln('</aside>');

if ($auth_user["javascript_enabled"]) {
	if ($auth_zid == "") {
		$last_seen = 0;
	} else {
		if (db_has_rec("pipe_view", array("pid" => $pid, "zid" => $auth_zid))) {
			$view = db_get_rec("pipe_view", array("pid" => $pid, "zid" => $auth_zid));
			$view["last_time"] = $view["time"];
			$last_seen = $view["time"];
		} else {
			$view = array();
			$view["pid"] = $pid;
			$view["zid"] = $auth_zid;
			$view["last_time"] = 0;
			$last_seen = 0;
		}
		$view["time"] = time();
		db_set_rec("pipe_view", $view);
	}

	writeln('<script>');
	writeln();
	writeln('var hide_value = ' . $hide_value . ';');
	writeln('var expand_value = ' . $expand_value . ';');
	writeln('var auth_zid = "' . $auth_zid . '";');
	writeln('var last_seen = ' . $last_seen . ';');
	writeln();
	writeln('get_comments(0, ' . $pid . ', 0);');
	writeln('render_page();');
	writeln();
	writeln('</script>');
}

print_footer();
