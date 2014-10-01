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

if (string_uses($s2, "[0-9]-") && string_uses($s3, "[a-z][0-9]-")) {
	$date = $s2;
	$slug = $s3;
	$time_beg = strtotime("$date GMT");
	if ($time_beg === false) {
		die("invalid date [$date]");
	}
	$time_end = $time_beg + 86400;

	$row = sql("select journal_id from journal where publish_time > ? and publish_time < ? and slug = ? order by publish_time", $time_beg, $time_end, $slug);
	if (count($row) == 0) {
		die("journal not found - date [$date] title [$slug]");
	}
	$journal_id = $row[0]["journal_id"];
} else if (string_uses($s2, "[a-z][0-9]_")) {
	$journal_id = $s2;
} else if (string_uses($s2, "[A-Z][a-z][0-9]")) {
	$short_id = crypt_crockford_decode($s2);
	$short = db_get_rec("short", $short_id);
	if ($short["type"] != "journal") {
		die("invalid short code [$s2]");
	}
	$journal_id = $short["item_id"];
} else {
	die("invalid request");
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

$journal = db_get_rec("journal", $journal_id);

if (!$journal["published"] && $zid != $auth_zid) {
	die("not published");
}

if ($auth_zid === $zid) {
	print_header("Journal", array("Write"), array("notepad"), array("/journal/write"));
} else {
	print_header("Journal");
}
print_left_bar("user", "journal");
beg_main("cell");

print_journal($journal_id);

if ($auth_user["javascript_enabled"]) {
	render_sliders("journal", $journal_id);
	print_noscript();
} else {
	render_page("journal", $journal_id, false);
}

end_main();

$last_seen = update_view_time("journal", $journal_id);

if ($auth_user["javascript_enabled"]) {
	writeln('<script>');
	writeln();
	writeln('var hide_value = ' . $hide_value . ';');
	writeln('var expand_value = ' . $expand_value . ';');
	writeln('var auth_zid = "' . $auth_zid . '";');
	writeln('var last_seen = ' . $last_seen . ';');
	writeln();
	writeln('get_comments("journal", "' . $journal_id . '");');
	writeln('render_page();');
	writeln();
	writeln('</script>');
}

print_footer();
