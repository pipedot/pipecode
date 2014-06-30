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
	$slug = $s3;
	$time_beg = strtotime("$date GMT");
	if ($time_beg === false) {
		die("invalid date [$date]");
	}
	$time_end = $time_beg + 86400;

	$row = run_sql("select sid from story where publish_time > ? and publish_time < ? and slug = ?", array($time_beg, $time_end, $slug));
	if (count($row) == 0) {
		die("story not found - date [$date] title [$slug]");
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
print_left_bar("main", "stories");
beg_main("cell");

print_story($sid);

if ($auth_user["javascript_enabled"]) {
	render_sliders($sid, 0, 0);
	print_noscript();
} else {
	render_page($sid, 0, 0, false);
}

end_main();

if ($auth_user["javascript_enabled"]) {
	if ($auth_zid == "") {
		$last_seen = 0;
	} else {
		if (db_has_rec("story_view", array("sid" => $sid, "zid" => $auth_zid))) {
			$view = db_get_rec("story_view", array("sid" => $sid, "zid" => $auth_zid));
			$view["last_time"] = $view["time"];
			$last_seen = $view["time"];
		} else {
			$view = array();
			$view["sid"] = $sid;
			$view["zid"] = $auth_zid;
			$view["last_time"] = 0;
			$last_seen = 0;
		}
		$view["time"] = time();
		db_set_rec("story_view", $view);
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
