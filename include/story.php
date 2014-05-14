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

function print_story($sid, $ipos = "right")
{
	global $server_name;
	global $auth_user;

	$story = db_get_rec("story", $sid);
	$pipe = db_get_rec("pipe", $story["pid"]);
	$topic = db_get_rec("topic", $story["tid"]);

	$a["story"] = $story["story"];
	$a["time"] = $story["time"];
	$a["sid"] = $sid;
	$a["ipos"] = $ipos;
	$a["topic"] = $topic["topic"];
	$a["icon"] = $story["icon"];
	$a["title"] = $story["title"];
	$a["pid"] = $story["pid"];
	$a["ipos"] = $ipos;
	$a["zid"] = $pipe["zid"];
	if ($sid > 0) {
		$row = run_sql("select count(cid) as comments from comment where sid = ?", array($sid));
		$a["comments"] = $row[0]["comments"];
	} else {
		$a["comments"] = 0;
	}

	print_article($a);
}


function print_article($a)
{
	global $server_name;
	global $auth_user;
	global $protocol;

	if (array_key_exists("time", $a)) {
		$time = $a["time"];
	} else {
		$time = time();
	}
	if (array_key_exists("ipos", $a)) {
		$ipos = $a["ipos"];
	} else {
		$ipos = "right";
	}
	$zid = $a["zid"];
	if ($zid == "") {
		$by = "<b>Anonymous Coward</b>";
	} else {
		$by = "<a href=\"" . user_page_link($zid) . "\"><b>$zid</b></a>";
	}
	if (array_key_exists("sid", $a)) {
		$sid = $a["sid"];
	} else {
		$sid = 0;
	}
	if (array_key_exists("pid", $a)) {
		$pid = $a["pid"];
	} else {
		$pid = 0;
	}
	if (array_key_exists("comments", $a)) {
		$comments = $a["comments"];
	} else {
		$comments = 0;
	}
	if (array_key_exists("pid", $a)) {
		$pid = $a["pid"];
	} else {
		$pid = 0;
	}
	if (array_key_exists("score", $a)) {
		$score = $a["score"];
	} else {
		$score = 0;
	}
	$topic = $a["topic"];
	$story = $a["story"];
	$icon = $a["icon"];
	$title = $a["title"];
	$ctitle = clean_url($title);
	$date = gmdate("Y-m-d H:i", $time);
	$day = gmdate("Y-m-d", $time);

	writeln("<article class=\"story\">");
	writeln("	<h1><a href=\"/story/$day/$ctitle\">$title</a><img alt=\"$topic\" class=\"story_icon_$ipos\" src=\"/images/$icon-64.png\"/></h1>");
	writeln("	<h2>by $by in <a href=\"$protocol://$server_name/topic/$topic\"><b>$topic</b></a> on $date (<a href=\"/pipe/$pid\">#$pid</a>)</h2>");
	writeln("	<div>$story</div>");
	writeln("	<footer>");
	writeln('		<table class="fill">');
	writeln('			<tr>');
	if ($sid > 0) {
		writeln("				<td><a href=\"/story/$day/$ctitle\"><b>$comments</b> comments</a></td>");
		if (@$auth_user["editor"]) {
			writeln("				<td class=\"right\"><a href=\"/story/$sid/edit\" class=\"icon_16\" style=\"background-image: url('/images/notepad-16.png')\">Edit</a></td>");
		}
	} else if ($pid > 0) {
		writeln("				<td><b>$comments</b> comments</td>");
		writeln("				<td class=\"right\">score <b>$score</b></td>");
	}
	writeln('			</tr>');
	writeln('		</table>');
	writeln("	</footer>");
	writeln("</article>");
}

