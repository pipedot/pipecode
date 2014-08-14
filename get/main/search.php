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

include("search.php");

$needle = http_get_string("needle", array("required" => false, "valid" => "[a-z][A-Z][0-9]_+-=%|./? "));
$haystack = http_get_string("haystack", array("required" => false, "len" => 20, "valid" => "[a-z]"));

if ($needle != "") {
	$needle = str_replace("+", " ", $needle);
	$needle = str_replace("%2B", "+", $needle);

	if ($haystack == "comments") {
		$sql = "select * , match (subject, body) against (? in boolean mode) as relevance from comment where match (subject, body) against (? in boolean mode) order by relevance";
	} else if ($haystack == "stories") {
		$sql = "select * , match (title, body) against (? in boolean mode) as relevance from story where match (title, body) against (? in boolean mode) order by relevance";
	} else if ($haystack == "pipe") {
		$sql = "select * , match (title, body) against (? in boolean mode) as relevance from pipe where match (title, body) against (? in boolean mode) order by relevance";
	} else if ($haystack == "polls") {
		$sql = "select * , match (question) against (? in boolean mode) as relevance from poll where match (question) against (? in boolean mode) order by relevance";
	} else {
		die("unknown haystack [$haystack]");
	}

	print_header("$needle - Search");

	print_left_bar("main", "search");

	beg_main("search");
	search_box($needle, $haystack);
	$row = sql($sql, $needle, $needle);
	if (count($row) == 0) {
		writeln('(no results)');
	}
	for ($i = 0; $i < count($row); $i++) {
		if ($haystack == "comments") {
			$title = $row[$i]["subject"];
			$link = "/comment/" . $row[$i]["comment_id"];
			$body = $row[$i]["body"];
			$time = $row[$i]["time"];
			$zid = $row[$i]["zid"];
		} else if ($haystack == "stories") {
			$title = $row[$i]["title"];
			$body = $row[$i]["body"];
			$time = $row[$i]["publish_time"];
			$link = "/story/" . gmdate("Y-m-d", $row[$i]["publish_time"]) . "/" . $row[$i]["slug"];
			$zid = $row[$i]["author_zid"];
		} else if ($haystack == "pipe") {
			$title = $row[$i]["title"];
			$link = "/pipe/" . $row[$i]["pipe_id"];
			$body = $row[$i]["body"];
			$time = $row[$i]["time"];
			$zid = $row[$i]["author_zid"];
		} else if ($haystack == "polls") {
			$title = $row[$i]["question"];
			$link = "/poll/" . gmdate("Y-m-d", $row[$i]["time"]) . "/" . $row[$i]["slug"];
			$body = "";
			$time = $row[$i]["time"];
			$zid = $row[$i]["zid"];
		}

		search_result($title, $link, $zid, $time, $body);
	}
	end_main();

	print_footer();
	die();
}

print_header("Search");
print_left_bar("main", "search");
beg_main("search");
search_box();
end_main();
print_footer();
