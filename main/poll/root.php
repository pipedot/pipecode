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
print_left_bar("main", "poll");
beg_main("cell");

vote_box($qid, true, false);

if ($auth_user["javascript_enabled"]) {
	render_sliders(0, 0, $qid);
	print_noscript();
} else {
	render_page(0, 0, $qid, false);
}

end_main();

if ($auth_user["javascript_enabled"]) {
	if ($auth_zid == "") {
		$last_seen = 0;
	} else {
		if (db_has_rec("poll_history", array("qid" => $qid, "zid" => $auth_zid))) {
			$history = db_get_rec("poll_history", array("qid" => $qid, "zid" => $auth_zid));
			$history["last_time"] = $history["time"];
			$last_seen = $history["time"];
		} else {
			$history = array();
			$history["qid"] = $qid;
			$history["zid"] = $auth_zid;
			$history["last_time"] = 0;
			$last_seen = 0;
		}
		$history["time"] = time();
		db_set_rec("poll_history", $history);
	}

	writeln('<script>');
	writeln();
	writeln('var hide_value = ' . $hide_value . ';');
	writeln('var expand_value = ' . $expand_value . ';');
	writeln('var auth_zid = "' . $auth_zid . '";');
	writeln('var last_seen = ' . $last_seen . ';');
	writeln();
	writeln('get_comments(0, 0, ' . $qid . ');');
	writeln('render_page();');
	writeln();
	writeln('</script>');
}

print_footer();
