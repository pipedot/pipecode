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

function make_atom($topic)
{
	global $server_name;
	global $server_title;
	global $server_slogan;
	global $cache_enabled;

	$row = sql("select story_id, pipe.author_zid, story.publish_time, story.title, story.slug, story.body from story inner join pipe on story.pipe_id = pipe.pipe_id order by publish_time desc limit 10");
	if (count($row) > 0) {
		$updated = $row[0]["publish_time"];
	} else {
		$updated = time();
	}

	$body = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$body .= "<feed xmlns=\"http://www.w3.org/2005/Atom\">\n";
	$body .= "	<title type=\"text\">$server_title</title>\n";
	$body .= "	<subtitle type=\"text\">$server_slogan</subtitle>\n";
	$body .= "	<updated>" . gmdate(DATE_ATOM, $updated) . "</updated>\n";
	$body .= "	<id>http://$server_name/atom</id>\n";
	$body .= "	<link rel=\"alternate\" type=\"text/html\" hreflang=\"en\" href=\"http://$server_name/\"/>\n";
	$body .= "	<link rel=\"self\" type=\"application/atom+xml\" href=\"http://$server_name/atom\"/>\n";
	$body .= "	<icon>http://$server_name/favicon.ico</icon>\n";
	$body .= "	<logo>http://$server_name/images/logo-feed.png</logo>\n";

	for ($i = 0; $i < count($row); $i++) {
		$body .= "	<entry>\n";
		$body .= "		<id>http://$server_name/story/" . $row[$i]["story_id"] . "</id>\n";
		$body .= "		<title>" . $row[$i]["title"] . "</title>\n";
		$body .= "		<updated>" . gmdate(DATE_ATOM, $row[$i]["publish_time"]) . "</updated>\n";
		$body .= "		<link rel=\"alternate\" type=\"text/html\" href=\"http://$server_name/story/" . gmdate("Y-m-d", $row[$i]["publish_time"]) . "/" . $row[$i]["slug"] . "\"/>\n";
		$body .= "		<author>\n";
		if ($row[$i]["author_zid"] == "") {
			$body .= "			<name>Anonymous Coward</name>\n";
		} else {
			$body .= "			<name>" . $row[$i]["author_zid"] . "</name>\n";
			$body .= "			<uri>" . user_page_link($row[$i]["author_zid"]) . "</uri>\n";
		}
		$body .= "		</author>\n";
		$body .= "		<content type=\"html\">" . htmlspecialchars($row[$i]["body"]) . "</content>\n";
		$body .= "	</entry>\n";
	}

	$body .= "</feed>\n";

	$time = time();
	$etag = md5($body);

	if ($cache_enabled) {
		//cache_set("atom.$topic.time", $time);
		//cache_set("atom.$topic.etag", $etag);
		//cache_set("atom.$topic.body", $body);
		cache_set(array("atom.$topic.time" => $time, "atom.$topic.etag" => $etag, "atom.$topic.body" => $body));
	}

	return array($time, $etag, $body);
}


function list_map($list, $map)
{
	$a = array();
	for ($i = 0; $i < count($list); $i++) {
		if (array_key_exists($list[$i], $map)) {
			$a[] = $map[$list[$i]];
		} else {
			$a[] = false;
		}
	}

	return $a;
}


function print_atom($topic)
{
	global $cache_enabled;

	//if (false) {
	if ($cache_enabled) {
		//$time = cache_get("atom.$topic.time");
		//$etag = cache_get("atom.$topic.etag");
		//$body = cache_get("atom.$topic.body");

		//$a = cache_get(array("atom.$topic.time", "atom.$topic.etag", "atom.$topic.body"));
		//if (array_key_exists("atom.$topic.time", $a)) {
		//	$time = $a["atom.$topic.time"];
		//} else {
		//	$time = false;
		//}
		//if (array_key_exists("atom.$topic.etag", $a)) {
		//	$etag = $a["atom.$topic.etag"];
		//} else {
		//	$etag = false;
		//}
		//if (array_key_exists("atom.$topic.body", $a)) {
		//	$body = $a["atom.$topic.body"];
		//} else {
		//	$body = false;
		//}

		$list = array("atom.$topic.time", "atom.$topic.etag", "atom.$topic.body");
		$map = cache_get($list);
		list($time, $etag, $body) = list_map($list, $map);

		//var_dump($a);
		//die("time [$time] etag [$etag]");
	} else {
		$time = false;
		$etag = false;
		$body = false;
	}

	if ($time === false || $etag === false || $body === false) {
		list($time, $etag, $body) = make_atom($topic);
//		die("not cached\n");
//	} else {
//		die("cached\n");
	}

	if (!http_modified($time, $etag)) {
		http_response_code(304);
	} else {
		header("Content-type: application/atom+xml");
		header("Last-Modified: " . gmdate("D, j M Y H:i:s", $time) . " GMT");
		header("ETag: \"$etag\"");

		print $body;
	}
}


function http_modified($time, $etag) {
	return !((isset($_SERVER["HTTP_IF_MODIFIED_SINCE"]) && strtotime($_SERVER["HTTP_IF_MODIFIED_SINCE"]) >= $time) || (isset($_SERVER["HTTP_IF_NONE_MATCH"]) && $_SERVER["HTTP_IF_NONE_MATCH"] == $etag));
}

