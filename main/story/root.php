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
include("story.php");

if (string_uses($s2, "[0-9]")) {
	$sid = (int) $s2;
} else {
	$date = $s2;
	$ctitle = $s3;
	$time_beg = strtotime("$date GMT");
	if ($time_beg === false) {
		die("invalid date [$date]");
	}
	$time_end = $time_beg + 86400;

	$row = run_sql("select sid from story where time > ? and time < ? and ctitle = ?", array($time_beg, $time_end, $ctitle));
	if (count($row) == 0) {
		die("story not found - date [$date] title [$ctitle]");
	}
	$sid = $row[0]["sid"];
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

$story = db_get_rec("story", $sid);

print_header($story["title"]);

writeln('<table class="fill">');
writeln('<tr>');
writeln('<td class="left_col">');
print_left_bar("main", "stories");
writeln('</td>');
writeln('<td class="fill">');

print_story($sid);
//print_story($sid, true, false);

if ($auth_user["javascript_enabled"]) {
	render_sliders($sid, 0, 0);
	print_noscript();
} else {
	render_page($sid, 0, 0, false);
}

writeln('</td>');
writeln('</tr>');
writeln('</table>');

if ($auth_user["javascript_enabled"]) {
	if ($auth_zid == "") {
		$last_seen = 0;
	} else {
		if (db_has_rec("story_history", array("sid" => $sid, "zid" => $auth_zid))) {
			$history = db_get_rec("story_history", array("sid" => $sid, "zid" => $auth_zid));
			$history["last_time"] = $history["time"];
			$last_seen = $history["time"];
		} else {
			$history = array();
			$history["sid"] = $sid;
			$history["zid"] = $auth_zid;
			$history["last_time"] = 0;
			$last_seen = 0;
		}
		$history["time"] = time();
		db_set_rec("story_history", $history);
	}

	writeln('<script>');
	writeln();
	writeln('var hide_value = ' . $hide_value . ';');
	writeln('var expand_value = ' . $expand_value . ';');
	writeln('var auth_zid = "' . $auth_zid . '";');
	writeln('var last_seen = ' . $last_seen . ';');
	writeln();
	writeln('get_comments(' . $sid . ', 0, 0);');
	writeln('render_page();');
	writeln();
	writeln('</script>');
}

print_footer();
