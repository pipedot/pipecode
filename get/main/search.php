<?
//
// Pipecode - distributed social network
// Copyright (C) 2014-2015 Bryan Beicker <bryan@pipedot.org>
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

include("search.php");
include("story.php");
include("pipe.php");
include("poll.php");

$needle = http_get_string("needle", array("required" => false, "valid" => "[a-z][A-Z][0-9]_+-=%|./? "));
$haystack = http_get_string("haystack", array("required" => false, "len" => 20, "valid" => "[a-z]"));

if ($needle != "") {
	$needle = str_replace("+", " ", $needle);
	$needle = str_replace("%2B", "+", $needle);

	if ($haystack == "comments") {
		if ($auth_user["show_junk_enabled"]) {
			$sql = "select * , match (subject, body) against (? in boolean mode) as relevance from comment where match (subject, body) against (? in boolean mode) order by relevance";
		} else {
			$sql = "select * , match (subject, body) against (? in boolean mode) as relevance from comment where match (subject, body) against (? in boolean mode) and junk_status <= 0 order by relevance";
		}
	} else if ($haystack == "stories") {
		$sql = "select * , match (title, body) against (? in boolean mode) as relevance from story where match (title, body) against (? in boolean mode) order by relevance";
	} else if ($haystack == "pipe") {
		$sql = "select * , match (title, body) against (? in boolean mode) as relevance from pipe where match (title, body) against (? in boolean mode) order by relevance";
	} else if ($haystack == "polls") {
		$sql = "select * , match (question) against (? in boolean mode) as relevance from poll where match (question) against (? in boolean mode) order by relevance";
	} else {
		fatal("Unknown haystack");
	}

	$spinner[] = ["name" => "Search", "link" => "/search/"];

	print_header(["title" => "$needle - Search"]);
	//print_main_nav("search");

	//beg_main("search");
	search_box($needle, $haystack);
	$row = sql($sql, $needle, $needle);
	if (count($row) == 0) {
		writeln('(no results)');
	}
	for ($i = 0; $i < count($row); $i++) {
		if ($haystack == "comments") {
			$title = $row[$i]["subject"];
			$link = "/comment/" . crypt_crockford_encode($row[$i]["comment_id"]);
			$body = $row[$i]["body"];
			$time = $row[$i]["edit_time"];
			$zid = $row[$i]["zid"];
			$a = article_info($row[$i]);

			//search_result($title, $link, $zid, $time, $body);
			print render_comment($row[$i]["subject"], $row[$i]["zid"], $row[$i]["publish_time"], $row[$i]["comment_id"], $row[$i]["body"], 0, $a["link"], $a["title"], $row[$i]["junk_status"]); //, $last_seen = 0, $article_link = "", $article_title = "", $junk_status = 0)
			writeln('</div>');
			writeln('</article>');
		} else if ($haystack == "stories") {
			$title = $row[$i]["title"];
			$body = $row[$i]["body"];
			$time = $row[$i]["publish_time"];
			$link = "/story/" . gmdate("Y-m-d", $row[$i]["publish_time"]) . "/" . $row[$i]["slug"];
			$zid = $row[$i]["author_zid"];

			print_story($row[$i]["story_id"]);
			//search_result($title, $link, $zid, $time, $body);
		} else if ($haystack == "pipe") {
			$title = $row[$i]["title"];
			$link = "/pipe/" . $row[$i]["pipe_id"];
			$body = $row[$i]["body"];
			$time = $row[$i]["time"];
			$zid = $row[$i]["author_zid"];

			print_pipe($row[$i]["pipe_id"]);
			//search_result($title, $link, $zid, $time, $body);
		} else if ($haystack == "polls") {
			$title = $row[$i]["question"];
			$link = "/poll/" . gmdate("Y-m-d", $row[$i]["publish_time"]) . "/" . $row[$i]["slug"];
			$body = "";
			$time = $row[$i]["publish_time"];
			$zid = $row[$i]["zid"];

			vote_box($row[$i]["poll_id"], false);
			//search_result($title, $link, $zid, $time, $body);
		}
	}
	//end_main();

	print_footer();
	finish();
}

$spinner[] = ["name" => "Search", "link" => "/search/"];

print_header();
//print_main_nav("search");
//beg_main("search");
search_box();
//end_main();
print_footer();
