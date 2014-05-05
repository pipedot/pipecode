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
if ($pipe["closed"]) {
	$status = "Closed";
	$row = run_sql("select sid from story where pid = ?", array($pid));
	if (count($row) > 0) {
		$status = '<a href="/story/' . $row[0]["sid"] . '">Published</a>';
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

writeln('<table class="fill">');
writeln('<tr>');
writeln('<td class="left_col" rowspan="2">');
print_left_bar("main", "pipe");
writeln('</td>');
writeln('<td class="fill">');

print_pipe($pid);

writeln('</td>');
writeln('<td class="right_col">');

writeln('<div class="right_bar">');
writeln('<div class="dialog_title">Status</div>');
writeln('<div class="dialog_body">');
writeln('	<div class="pipe_status">' . $status . $reason . '</div>');
writeln('</div>');

if ($pipe["editor"] === "") {
	if ($auth_user["editor"]) {
		writeln('<div class="dialog_title">Editor</div>');
		writeln('<div class="dialog_body">');
		writeln('	<div class="pipe_editor"><a href="/pipe/' . $pid . '/publish">Publish</a> | <a href="/pipe/' . $pid . '/close">Close</a></div>');
		writeln('</div>');
	}
} else {
	writeln('<div class="dialog_title">Editor</div>');
	writeln('<div class="dialog_body">');
	writeln('	<div class="pipe_editor"><a href="' . user_page_link($pipe["editor"]) . '"><b>' . $pipe["editor"] . '</b></a></div>');
	writeln('</div>');
}
writeln('</div>');

writeln('</td>');
writeln('</tr>');
writeln('<tr>');
writeln('<td style="vertical-align: top" colspan="2">');

if ($javascript_enabled) {
	render_sliders(0, $pid, 0);
	print_noscript();
} else {
	render_page(0, $pid, 0, false);
}

writeln('</td>');
writeln('</tr>');
writeln('</table>');

if ($javascript_enabled) {
	writeln('<script>');
	writeln();
	writeln('var hide_value = ' . $hide_value . ';');
	writeln('var expand_value = ' . $expand_value . ';');
	writeln('var auth_zid = "' . $auth_zid . '";');
	writeln();
	writeln('get_comments(0, ' . $pid . ', 0);');
	writeln('render_page();');
	writeln();
	writeln('</script>');
}

print_footer();
