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

if (string_has($s2, "-") && $s3 === "") {
	$date = $s2;
	$time_beg = strtotime("$date GMT");
	if ($time_beg === false) {
		die("invalid date [$date]");
	}
	$time_end = $time_beg + 86400;

	print_header();
	print_left_bar("main", "stories");
	beg_main("cell");

	$row = sql("select story_id from story where publish_time > ? and publish_time < ? order by publish_time desc", $time_beg, $time_end);
	if (count($row) == 0) {
		writeln("No stories published on [" . gmdate("Y-m-d", $time_beg) . "]");
	}
	for ($i = 0; $i < count($row); $i++) {
		print_story($row[$i]["story_id"]);
	}

	end_main();
	print_footer();
} else {
	$story = find_rec("story");

	print_header($story["title"]);
	print_left_bar("main", "stories");
	beg_main("cell");

	print_story($story);
	print_comments("story", $story);

	end_main();
	print_footer();
}
