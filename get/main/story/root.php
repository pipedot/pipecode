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

if (string_uses($s2, "[0-9]-")) {
	$date = $s2;
	$slug = $s3;
	$time_beg = strtotime("$date GMT");
	if ($time_beg === false) {
		die("invalid date [$date]");
	}
	$time_end = $time_beg + 86400;
	if (!string_uses($slug, "[a-z][0-9]-")) {
		die("invalid slug [$slug]");
	}

	$row = sql("select story_id from story where publish_time > ? and publish_time < ? and slug = ? order by publish_time", $time_beg, $time_end, $slug);
	if (count($row) == 0) {
		die("story not found - date [$date] title [$slug]");
	}
	$story_id = $row[0]["story_id"];
} else if (string_uses($s2, "[a-z][0-9]_")) {
	$story_id = $s2;
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

$story = db_get_rec("story", $story_id);

if (string_has($story["author_zid"], $import_server_name)) {
	$soylentnews_story = db_get_rec("soylentnews_story", array("story_id" => $story_id));
	$canonical_uri = $protocol . "://soylentnews.org/article.pl?sid=" . $soylentnews_story["sid_date"];
	if (!$auth_user["soylentnews_enabled"]) {
		header("Location: $canonical_uri");
		die();
	}
}

print_header($story["title"]);
print_left_bar("main", "stories");
beg_main("cell");

print_story($story_id);

if (string_has($story["author_zid"], $import_server_name)) {
	writeln('<a class="mirror_balloon" href="' . $canonical_uri . '">');
	writeln('<h1>SoylentNews Post</h1>');
	writeln('<p>This post originated on SoylentNews, a very active and friendly group of volunteers attempting to make the best "News for Nerds" site on the Internet.</p>');
	writeln('<p>Please click anywhere on this message to go to the canonical URI for this article. This page is merely a mirror; more information on this gateway can be found on the Pipedot about page.</p>');
	writeln('</a>');
}

if ($auth_user["javascript_enabled"]) {
	render_sliders("story", $story_id);
	print_noscript();
} else {
	render_page("story", $story_id, false);
}

end_main();

$last_seen = update_view_time("story", $story_id);

if ($auth_user["javascript_enabled"]) {
	writeln('<script>');
	writeln();
	writeln('var hide_value = ' . $hide_value . ';');
	writeln('var expand_value = ' . $expand_value . ';');
	writeln('var auth_zid = "' . $auth_zid . '";');
	writeln('var last_seen = ' . $last_seen . ';');
	writeln();
	writeln('get_comments("story", "' . $story_id . '");');
	writeln('render_page();');
	writeln();
	writeln('</script>');
}

print_footer();
