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
include("poll.php");

$qid = (int) $s2;
if ($qid == "") {
	$row = run_sql("select max(qid) as qid from poll_question");
	$qid = $row[0]["qid"];
	$vote = true;
}

$poll_question = db_get_rec("poll_question", $qid);
$clean = clean_url($poll_question["question"]);
$type_id = $poll_question["type_id"];

if ($auth_zid == "") {
	$can_moderate = true;
	$hide_value = $auth_user["hide_threshold"];
	$expand_value = $auth_user["expand_threshold"];
} else {
	$can_moderate = false;
	$hide_value = -1;
	$expand_value = 0;
}

print_header("Poll");

writeln('<table class="fill">');
writeln('<tr>');
writeln('<td class="left_col">');
print_left_bar("main", "poll");
writeln('</td>');
writeln('<td class="fill">');

vote_box($qid, true, false);

if ($javascript_enabled) {
	render_sliders(0, 0, $qid);
	print_noscript();
} else {
	render_page(0, 0, $qid, false);
}

writeln('		</td>');
writeln('	</tr>');
writeln('</table>');

if ($javascript_enabled) {
	writeln('<script>');
	writeln();
	writeln('var hide_value = ' . $hide_value . ';');
	writeln('var expand_value = ' . $expand_value . ';');
	writeln('var auth_zid = "' . $auth_zid . '";');
	writeln();
	writeln('get_comments(0, 0, ' . $qid . ');');
	writeln('render_page();');
	writeln();
	writeln('</script>');
}

print_footer();
