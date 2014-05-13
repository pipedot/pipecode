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

function search_box($needle = "", $haystack = "comments")
{
	beg_form("", false));
	writeln('<table class="round">');
	writeln('	<tr>');
	writeln('		<td rowspan="2" style="width: 64px"><img alt="Search" src="/images/magnifier-64.png"/></td>');
	writeln('		<td style="width: 100%; vertical-align: bottom"><input type="search" name="needle" value="' . $needle . '" required="required"/></td>');
	writeln('		<td style="width: 64px; vertical-align: bottom"><input type="submit" value="Search"/></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td colspan="2" style="vertical-align: top">');
	if ($haystack == "comments") {
		writeln('			<label><input type="radio" name="haystack" value="comments" checked="checked"/>Comments</label>');
	} else {
		writeln('			<label><input type="radio" name="haystack" value="comments"/>Comments</label>');
	}
	if ($haystack == "stories") {
		writeln('			<label><input type="radio" name="haystack" value="stories" checked="checked"/>Stories</label>');
	} else {
		writeln('			<label><input type="radio" name="haystack" value="stories"/>Stories</label>');
	}
	if ($haystack == "pipe") {
		writeln('			<label><input type="radio" name="haystack" value="pipe" checked="checked"/>Pipe</label>');
	} else {
		writeln('			<label><input type="radio" name="haystack" value="pipe"/>Pipe</label>');
	}
	if ($haystack == "polls") {
		writeln('			<label><input type="radio" name="haystack" value="polls" checked="checked"/>Polls</label>');
	} else {
		writeln('			<label><input type="radio" name="haystack" value="polls"/>Polls</label>');
	}
	writeln('		</td>');
	writeln('	</tr>');
	writeln('</table>');
	end_form();
}


function search_result($title, $link, $zid, $time, $body)
{
	global $server_name;
	global $protocol;

	$date = date("Y-m-d H:i", $time);
	if ($zid == "") {
		$by = "Anonymous Coward";
	} else {
		$by = "<a href=\"" . user_page_link($zid) . "\">$zid</a>";
	}

	writeln("<article>");
	writeln("	<h1><a href=\"$link\">$title</a></h1>");
	writeln("	<h2>$protocol://$server_name$link</h2>");
	writeln("	<h3>by $by on $date</h3>");
	writeln("	<p>$body</p>");
	writeln("</article>");
}


$needle = http_get_string("needle", array("required" => false, "valid" => "[a-z][A-Z][0-9]_+-=%|./? "));
$haystack = http_get_string("haystack", array("required" => false, "len" => 20, "valid" => "[a-z]"));

if ($needle != "") {
	$needle = str_replace("+", " ", $needle);
	$needle = str_replace("%2B", "+", $needle);

	if ($haystack == "comments") {
		$sql = "select * , match (subject, comment) against (? in boolean mode) as relevance from comment where match (subject, comment) against (? in boolean mode) order by relevance";
	} else if ($haystack == "stories") {
		$sql = "select * , match (title, story) against (? in boolean mode) as relevance from story where match (title, story) against (? in boolean mode) order by relevance";
	} else if ($haystack == "pipe") {
		$sql = "select * , match (title, story) against (? in boolean mode) as relevance from pipe where match (title, story) against (? in boolean mode) order by relevance";
	} else if ($haystack == "polls") {
		$sql = "select * , match (question) against (? in boolean mode) as relevance from poll_question where match (question) against (? in boolean mode) order by relevance";
	} else {
		die("unknown haystack [$haystack]");
	}

	print_header("$needle - Search");

	writeln('<table class="fill">');
	writeln('<tr>');
	writeln('<td class="left_col">');
	print_left_bar("main", "search");
	writeln('</td>');
	writeln('<td class="fill">');

	search_box($needle, $haystack);

	writeln('<main class="search">');

	$row = run_sql($sql, array($needle, $needle));
	if (count($row) == 0) {
		writeln('(no results)');
	}
	//var_dump($row);
	for ($i = 0; $i < count($row); $i++) {
		if ($haystack == "comments") {
			$title = $row[$i]["subject"];
			$link = "/comment/" . $row[$i]["cid"];
			$body = $row[$i]["comment"];
			$zid = $row[$i]["zid"];
		} else if ($haystack == "stories") {
			$title = $row[$i]["title"];
			$link = "/story/" . $row[$i]["sid"];
			$body = $row[$i]["story"];
			$pipe = db_get_rec("pipe", $row[$i]["pid"]);
			$zid = $pipe["zid"];
		} else if ($haystack == "pipe") {
			$title = $row[$i]["title"];
			$link = "/pipe/" . $row[$i]["pid"];
			$body = $row[$i]["story"];
			$zid = $row[$i]["zid"];
		} else if ($haystack == "polls") {
			$title = $row[$i]["question"];
			$link = "/poll/" . $row[$i]["qid"];
			$body = "";
			$zid = $row[$i]["zid"];
		}

		search_result($title, $link, $zid, $row[$i]["time"], $body);
	}

	writeln('</main>');

	writeln('</td>');
	writeln('</tr>');
	writeln('</table>');

	print_footer();
	die();
}

print_header("Search");

writeln('<table class="fill">');
writeln('<tr>');
writeln('<td class="left_col">');
print_left_bar("main", "search");
writeln('</td>');
writeln('<td class="fill">');

search_box();

writeln('</td>');
writeln('</tr>');
writeln('</table>');

print_footer();
