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

function make_atom($topic)
{
	global $server_name;
	global $server_title;
	global $server_slogan;
	global $cache_enabled;
	global $protocol;

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
	$body .= "	<id>$protocol://$server_name/atom</id>\n";
	$body .= "	<link rel=\"alternate\" type=\"text/html\" hreflang=\"en\" href=\"$protocol://$server_name/\"/>\n";
	$body .= "	<link rel=\"self\" type=\"application/atom+xml\" href=\"$protocol://$server_name/atom\"/>\n";
	$body .= "	<icon>$protocol://$server_name/favicon.ico</icon>\n";
	$body .= "	<logo>$protocol://$server_name/images/logo-feed.png</logo>\n";

	for ($i = 0; $i < count($row); $i++) {
		$story_code = crypt_crockford_encode($row[$i]["story_id"]);

		$body .= "	<entry>\n";
		$body .= "		<id>$protocol://$server_name/story/$story_code</id>\n";
		$body .= "		<title>" . $row[$i]["title"] . "</title>\n";
		$body .= "		<updated>" . gmdate(DATE_ATOM, $row[$i]["publish_time"]) . "</updated>\n";
		$body .= "		<link rel=\"alternate\" type=\"text/html\" href=\"$protocol://$server_name/story/" . gmdate("Y-m-d", $row[$i]["publish_time"]) . "/" . $row[$i]["slug"] . "\"/>\n";
		$body .= "		<author>\n";
		if ($row[$i]["author_zid"] == "") {
			$body .= "			<name>Anonymous Coward</name>\n";
		} else {
			$body .= "			<name>" . $row[$i]["author_zid"] . "</name>\n";
			$body .= "			<uri>" . user_link($row[$i]["author_zid"]) . "</uri>\n";
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


function make_comment_atom($topic)
{
	global $server_name;
	global $server_title;
	global $server_slogan;
	global $cache_enabled;
	global $protocol;
	global $auth_user;

	if ($auth_user["show_junk_enabled"]) {
		$row = sql("select comment_id, root_id, subject, type, edit_time, body, zid from comment order by edit_time desc limit 50");
	} else {
		$row = sql("select comment_id, root_id, subject, type, edit_time, body, zid from comment where junk_status <= 0 order by edit_time desc limit 50");
	}
	if (count($row) > 0) {
		$updated = $row[0]["edit_time"];
	} else {
		$updated = time();
	}

	$body = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$body .= "<feed xmlns=\"http://www.w3.org/2005/Atom\">\n";
	$body .= "	<title type=\"text\">$server_title</title>\n";
	$body .= "	<subtitle type=\"text\">Recent Comments</subtitle>\n";
	$body .= "	<updated>" . gmdate(DATE_ATOM, $updated) . "</updated>\n";
	$body .= "	<id>$protocol://$server_name/comment/atom</id>\n";
	$body .= "	<link rel=\"alternate\" type=\"text/html\" hreflang=\"en\" href=\"$protocol://$server_name/comment/\"/>\n";
	$body .= "	<link rel=\"self\" type=\"application/atom+xml\" href=\"$protocol://$server_name/comment/atom\"/>\n";
	$body .= "	<icon>$protocol://$server_name/favicon.ico</icon>\n";
	$body .= "	<logo>$protocol://$server_name/images/logo-feed.png</logo>\n";

	for ($i = 0; $i < count($row); $i++) {
		$comment_code = crypt_crockford_encode($row[$i]["comment_id"]);
		$comment_type = $row[$i]["type"];
		$article = db_get_rec($comment_type, $row[$i]["root_id"]);
		if ($comment_type == "poll") {
			$article_title = $article["question"];
		} else {
			$article_title = $article["title"];
		}
		if ($comment_type == "pipe") {
			$artitle_time = gmdate(DATE_ATOM, $article["time"]);
		} else {
			$article_time = gmdate(DATE_ATOM, $article["publish_time"]);
		}
		$article_link = item_link($comment_type, $article[$comment_type . "_id"], $article);

		$subtitle = "by " . user_link($row[$i]["zid"], ["tag" => true]);
		$subtitle .= " in <a href=\"$article_link\"><b>$article_title</b></a>";
		$subtitle .= " on <time datetime=\"" .  gmdate("c", $row[$i]["edit_time"]) . "\">" . gmdate("Y-m-d H:i", $row[$i]["edit_time"]) . "</time>";
		$subtitle .= " (<a href=\"$protocol://$server_name/$comment_code\">#$comment_code</a>)";

		$body .= "	<entry>\n";
		$body .= "		<id>$protocol://$server_name/comment/$comment_code</id>\n";
		$body .= "		<title>" . $row[$i]["subject"] . "</title>\n";
		$body .= "		<updated>" . gmdate(DATE_ATOM, $row[$i]["edit_time"]) . "</updated>\n";
		$body .= "		<link rel=\"alternate\" type=\"text/html\" href=\"$protocol://$server_name/comment/$comment_code\"/>\n";
		$body .= "		<author>\n";
		if ($row[$i]["zid"] == "") {
			$body .= "			<name>Anonymous Coward</name>\n";
		} else {
			$body .= "			<name>" . $row[$i]["zid"] . "</name>\n";
			$body .= "			<uri>" . user_link($row[$i]["zid"]) . "</uri>\n";
		}
		$body .= "		</author>\n";
		$body .= "		<source>\n";
		$body .= "			<id>$article_title</id>\n";
		$body .= "			<title>$article_title</title>\n";
		$body .= "			<updated>$article_time</updated>\n";
		$body .= "		</source>\n";
		//$body .= "		<content type=\"html\">" . htmlspecialchars($row[$i]["body"]) . "</content>\n";
		$body .= "		<content type=\"html\">" . htmlspecialchars("<p>$subtitle</p><p>" . $row[$i]["body"] . "</p>") . "</content>\n";
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


function make_journal_atom($zid)
{
	global $server_name;
	global $server_title;
	global $server_slogan;
	global $cache_enabled;
	global $protocol;

	$row = sql("select journal_id, body, publish_time, slug, title from journal where zid = ? and published = 1 order by publish_time desc limit 10", $zid);
	if (count($row) > 0) {
		$updated = $row[0]["publish_time"];
	} else {
		$updated = time();
	}

	$body = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$body .= "<feed xmlns=\"http://www.w3.org/2005/Atom\">\n";
	$body .= "	<title type=\"text\">$zid</title>\n";
	$body .= "	<subtitle type=\"text\">Journal</subtitle>\n";
	$body .= "	<updated>" . gmdate(DATE_ATOM, $updated) . "</updated>\n";
	$body .= "	<id>$protocol://$server_name/atom</id>\n";
	$body .= "	<link rel=\"alternate\" type=\"text/html\" hreflang=\"en\" href=\"" . user_link($zid) . "journal/\"/>\n";
	$body .= "	<link rel=\"self\" type=\"application/atom+xml\" href=\"" . user_link($zid) . "journal/atom\"/>\n";
	$body .= "	<icon>$protocol://$server_name/favicon.ico</icon>\n";
	$body .= "	<logo>" . profile_picture($zid, 256) . "</logo>\n";

	for ($i = 0; $i < count($row); $i++) {
		$journal_code = crypt_crockford_encode($row[$i]["journal_id"]);

		$body .= "	<entry>\n";
		$body .= "		<id>" . user_link($zid) . "journal/$journal_code</id>\n";
		$body .= "		<title>" . $row[$i]["title"] . "</title>\n";
		$body .= "		<updated>" . gmdate(DATE_ATOM, $row[$i]["publish_time"]) . "</updated>\n";
		$body .= "		<link rel=\"alternate\" type=\"text/html\" href=\"" . user_link($zid) . "journal/" . gmdate("Y-m-d", $row[$i]["publish_time"]) . "/" . $row[$i]["slug"] . "\"/>\n";
		$body .= "		<author>\n";
		$body .= "			<name>$zid</name>\n";
		$body .= "			<uri>" . user_link($zid) . "</uri>\n";
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


function print_atom($type, $topic)
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
		if ($type == "story") {
			list($time, $etag, $body) = make_atom($topic);
		} else if ($type == "comment") {
			list($time, $etag, $body) = make_comment_atom($topic);
		} else if ($type == "journal") {
			list($time, $etag, $body) = make_journal_atom($topic);
		}
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
