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


function import_stories($start = 0, $max_items = -1, $max_pages = -1, $force = false)
{
	global $doc_root;

	$done = false;
	$count = 0;
	$pages = 0;
	while (true) {
		$data = http_slurp("http://soylentnews.org/search.pl?op=stories&start=$start");

		$pos_result = 0;
		while (true) {
			$cid = 0;
			$sid = 0;

			$pos_result = strpos($data, '<div class="search-results">', $pos_result);
			//print "found pos_result [$pos_result]\n";
			if ($pos_result === false) {
				break;
			}
			$pos_beg = strpos($data, 'article.pl?sid=', $pos_result);
			if ($pos_beg !== false) {
				$pos_beg += 15;
				$pos_end = strpos($data, '"', $pos_beg);
				if ($pos_end !== false) {
					$sid_date = substr($data, $pos_beg, $pos_end - $pos_beg);
					if (!db_has_rec("soylentnews_story", array("sid_date" => $sid_date))) {
						print "story - sid_date sid_date [$sid_date]\n";
						import_story($sid_date);
						$count++;
						print "\n";
						$done = false;
					} else {
						$done = true;
					}
				}
			}

			$pos_result += 29;
		}
		if ($count >= $max_items && $max_items != -1) {
			return;
		}
		if ($pages > $max_pages && $max_pages != -1) {
			return;
		}
		if ($done && !$force) {
			print "found already imported page\n";
			return;
		}
		$start += 25;
		$pages++;
		print "next page! start [$start]\n\n";
	}
}


function import_story($sid_date)
{
	global $doc_root;

	$data = http_slurp("http://soylentnews.org/article.pl?sid=$sid_date");

	$sid = 0;
	$body = "";
	$edit_time = 0;
	$icon = "news";
	$publish_time = 0;
	$slug = "";
	$tid = 0;
	$title = "";
	$uid = 0;
	$username = "";
	$now = time();
	$topic = "";

	$title = get_tag($data, "div", "generaltitle", "class");
	$title = get_tag($title, "a", "//soylentnews.org/article.pl?sid=$sid_date", "href");

	$details = get_tag($data, "div", "details", "class");
	$beg = strpos($details, '<a href="');
	if ($beg !== false) {
		$end = strpos($details, '</a>', $beg);
		$username = substr($details, $beg, $end - $beg);
		$beg = strrpos($username, '">');
		if ($beg !== false) {
			$username = substr($username, $beg + 2);
		}
		$username = trim($username);

		$s = substr($details, $end + 4);
		$s = trim($s);
		if (substr($s, 0, 3) == "on ") {
			$s = substr($s, 3);
			$beg = strpos($s, " ");
			$end = strpos($s, "\n");
			if ($beg !== false && $end !== false) {
				$s = substr($s, $beg + 1, $end - $beg - 1);
				$s = str_replace("@", "", $s);
				$time = strtotime($s);
			}
		}


	}

	$s = get_tag($data, "div", "topic", "class");
	$beg = strpos($s, "search.pl?tid=");
	if ($beg !== false) {
		$end = strpos($s, '"', $beg);
		if ($end !== false) {
			$tid = substr($s, $beg + 14, $end - $beg - 14);
		}
	}
	$beg = strpos($s, 'title="');
	if ($beg !== false) {
		$end = strpos($s, '"', $beg + 7);
		if ($end !== false) {
			$topic = substr($s, $beg + 7, $end - $beg - 7);
		}
	}

	$body = str_replace("<blockquote><div>", "<blockquote>", $data);
	$body = str_replace("</div></blockquote>", "</blockquote>", $body);
	$body = get_tag($body, "div", "intro", "class");
	$body = str_replace("</p>", "</p><br><br>", $body);
	$body = clean_html($body, "comment");
	if (substr($body, -10) == "<br/><br/>") {
		$body = substr($body, 0, -10);
	}

	$s = get_tag($data, "div", "commentBox", "class");
	$end = strpos($s, '">Search Discussion</a>');
	if ($end !== false) {
		$s = substr($s, 0, $end);
		$beg = strrpos($s, "?");
		if ($beg !== false) {
			$s = substr($s, $beg + 1);
			$s = str_replace("&amp;", "&", $s);
			$map = map_from_url_string($s);
			if (array_key_exists("sid", $map)) {
				$sid = (int) $map["sid"];
			}
		}
	}

	$zid = import_user($username);
	$uid = get_uid_from_zid($zid);
	if ($topic == "") {
		$topic = "News";
		$tid = 6;
	} else {
		add_topic($tid, $topic);
	}

	//print "sid [$sid]\nsid_date [$sid_date]\nuid [$uid]\nusername [$username]\ntitle [$title]\ntid [$tid]\ntopic [$topic]\ntime [$time] [" . date("Y-m-d H:i", $time) . "]\nbody [$body]\n";
	//print "data [$data]\n";

	if ($sid > 0 && $body != "" && $title != "" && $tid > 0 && $time > 0) {
		if (db_has_rec("soylentnews_story", $sid)) {
			$soylentnews_story = db_get_rec("soylentnews_story", $sid);
			$story = db_get_rec("story", $soylentnews_story["story_id"]);
		} else {
			$story = array();
			$story["story_id"] = create_id($zid, $time);
			$soylentnews_story = array();
			$soylentnews_story["sid"] = $sid;
			$soylentnews_story["sid_date"] = $sid_date;
			$soylentnews_story["story_id"] = $story["story_id"];
		}
		$soylentnews_story["body"] = $body;
		$soylentnews_story["icon"] = "news";
		$soylentnews_story["last_sync"] = $now;
		$soylentnews_story["slug"] = clean_url($title);
		$soylentnews_story["tid"] = $tid;
		$soylentnews_story["time"] = $time;
		$soylentnews_story["title"] = $title;
		$soylentnews_story["uid"] = $uid;
		db_set_rec("soylentnews_story", $soylentnews_story);

		$native_topic = db_get_rec("topic", array("topic" => strtolower($topic)));
		$native_tid = $native_topic["tid"];

		$story["author_zid"] = $zid;
		$story["body"] = $body;
		$story["edit_time"] = $time;
		$story["edit_zid"] = $zid;
		$story["icon"] = "news";
		$story["image_id"] = 0;
		$story["pipe_id"] = "";
		$story["publish_time"] = $time;
		$story["short_id"] = create_short("story", $story["story_id"]);
		$story["slug"] = clean_url($title);
		$story["tid"] = $native_tid;
		$story["title"] = $title;
		$story["tweet_id"] = 0;
		db_set_rec("story", $story);

		$soylentnews_log = array();
		$soylentnews_log["time"] = $now;
		$soylentnews_log["type"] = "story";
		$soylentnews_log["item_id"] = $story["story_id"];
		db_set_rec("soylentnews_log", $soylentnews_log);
	}
}


function import_user($username)
{
	global $doc_root;

	if ($username == "Anonymous Coward") {
		return "";
	}

	$uid = 0;
	if (db_has_rec("soylentnews_user", array("username" => $username))) {
		$soylentnews_user = db_get_rec("soylentnews_user", array("username" => $username));
		return $soylentnews_user["zid"];
	}

	$data = http_slurp("http://soylentnews.org/~" . str_replace(" ", "+", $username));

	$beg = strpos($data, '<div class="title" id="user-info-title">');
	if ($beg !== false) {
		$s = substr($data, $beg + 40);
		$end = strpos($s, "</h4>");
		if ($end !== false) {
			$s = substr($s, 0, $end);
			$s = str_replace(")", "", $s);
			$beg = strrpos($s, "(");
			if ($beg !== false) {
				$uid = substr($s, $beg + 1);
				$uid = trim($uid);
				$uid = (int) $uid;
			}
		}
	}

	$zid = add_user($uid, $username);

	return $zid;
}


function get_uid_from_zid($zid)
{
	if (db_has_rec("soylentnews_user", array("zid" => $zid))) {
		$soylentnews_user = db_get_rec("soylentnews_user", array("zid" => $zid));
		return $soylentnews_user["uid"];
	}

	return 0;
}


function get_zid_from_uid($uid)
{
	if ($uid == 0) {
		return "";
	}

	if (db_has_rec("soylentnews_user", $uid)) {
		$soylentnews_user = db_get_rec("soylentnews_user", $uid);
		return $soylentnews_user["zid"];
	}

	return "";
}


function add_user($uid, $username)
{
	global $import_server_name;

	if ($uid <= 1) {
		return "";
	}
	if (db_has_rec("soylentnews_user", $uid)) {
		$soylentnews_user = db_get_rec("soylentnews_user", $uid);
		return $soylentnews_user["zid"];
	}

	$zid = strtolower($username);
	if (!string_uses($zid, "[a-z][0-9]")) {
		$zid = string_clean($zid, "[a-z][0-9]");
		$i = 1;
		while (is_local_user("$zid-$i@$import_server_name")) {
			$i++;
		}
		$zid = "$zid-$i@$import_server_name";
	} else {
		$zid = "$zid@$import_server_name";
	}
	$user_conf = db_get_conf("user_conf", $zid);
	$user_conf["password"] = "*";
	db_set_conf("user_conf", $user_conf, $zid);

	$soylentnews_user = array();
	$soylentnews_user["uid"] = $uid;
	$soylentnews_user["username"] = $username;
	$soylentnews_user["zid"] = $zid;
	db_set_rec("soylentnews_user", $soylentnews_user);

	$soylentnews_log = array();
	$soylentnews_log["time"] = time();
	$soylentnews_log["type"] = "user";
	$soylentnews_log["item_id"] = $zid;
	db_set_rec("soylentnews_log", $soylentnews_log);

	return $zid;
}


function import_comments($start = 0, $max_items = -1, $max_pages = -1, $force = false)
{
	global $doc_root;

	$done = false;
	$count = 0;
	$pages = 0;
	while (true) {
		$data = http_slurp("http://soylentnews.org/search.pl?op=comments&threshold=-1&start=$start");

		$pos_result = 0;
		while (true) {
			$cid = 0;
			$sid = 0;

			$pos_result = strpos($data, '<div class="search-results">', $pos_result);
			//print "found pos_result [$pos_result]\n";
			if ($pos_result === false) {
				break;
			}
			$pos_beg = strpos($data, 'comments.pl?', $pos_result);
			if ($pos_beg !== false) {
				$pos_beg += 12;
				$pos_end = strpos($data, '"', $pos_beg);
				if ($pos_end !== false) {
					$link = substr($data, $pos_beg, $pos_end - $pos_beg);
					$link = str_replace("&amp;", "&", $link);
					$map = map_from_url_string($link);
					if (array_key_exists("cid", $map) && array_key_exists("sid", $map)) {
						$cid = $map["cid"];
						$sid = $map["sid"];
						if (!db_has_rec("soylentnews_comment", $cid)) {
							print "comment - cid [$cid] sid [$sid]\n";
							import_comment($cid, $sid);
							$count++;
							print "\n";
							$done = false;
						} else {
							$done = true;
						}
					}
				}
			}

			$pos_result += 29;
		}
		if ($count >= $max_items && $max_items != -1) {
			return;
		}
		if ($pages > $max_pages && $max_pages != -1) {
			return;
		}
		if ($done && !$force) {
			return;
		}
		$start += 25;
		$pages++;
		print "next page! start [$start]\n\n";
	}
}


function get_tag($data, $tag, $id, $attr = "id")
{
	$s = "<$tag $attr=\"$id\"";
	$beg = strpos($data, $s);
	if ($beg !== false) {
		$beg = strpos($data, ">", $beg);
		if ($beg !== false) {
			$beg++;
			$end = strpos($data, "</$tag>", $beg);
			if ($end !== false) {
				return substr($data, $beg, $end - $beg);
			}
		}
	}

	return "";
}


function add_topic($tid, $topic)
{
	if (!db_has_rec("soylentnews_topic", $tid)) {
		$soylentnews_topic = array();
		$soylentnews_topic["tid"] = $tid;
		$soylentnews_topic["slug"] = clean_url($topic);
		$soylentnews_topic["topic"] = $topic;
		db_set_rec("soylentnews_topic", $soylentnews_topic);

		$soylentnews_log = array();
		$soylentnews_log["time"] = time();
		$soylentnews_log["type"] = "topic";
		$soylentnews_log["item_id"] = $topic;
		db_set_rec("soylentnews_log", $soylentnews_log);
	}

	$topic = strtolower($topic);
	if (!db_has_rec("topic", array("topic" => $topic))) {
		$native_topic = array();
		$native_topic["tid"] = 0;
		$native_topic["icon"] = "news";
		$native_topic["promoted"] = 0;
		$native_topic["slug"] = clean_url($topic);
		$native_topic["topic"] = $topic;
		db_set_rec("topic", $native_topic);
	}
}


function import_comment($cid, $sid)
{
	global $doc_root;
	global $server_name;
	global $import_server_name;

	$data = http_slurp("http://soylentnews.org/comments.pl?cid=$cid&sid=$sid");

	$title = "";
	$score = 0;
	$parent = 0;
	$rating = "";
	$time = 0;
	$body = "";
	$uid = 0;
	$username = "";
	$subject = "";
	$now = time();

	$subject = get_tag($data, "a", $cid, "name");

	$score = get_tag($data, "span", "comment_score_$cid");
	$score = str_replace("(Score:", "", $score);
	$score = str_replace(")", "", $score);
	$score = str_replace(" ", "", $score);
	if (string_has($score, ",")) {
		list($score, $rating) = explode(",", $score);
	}

	$details = get_tag($data, "div", "details", "class");
	if (string_has($details, "Anonymous Coward")) {
		$uid = 0;
		$username = "Anonymous Coward";
	} else {
		$beg = strpos($details, ">");
		$end = strpos($details, "</a>");
		if ($beg !== false && $end !== false) {
			$details = substr($details, $beg + 1, $end - $beg - 1);
		}
		$beg = strrpos($details, " ");
		if ($beg !== false) {
			$uid = substr($details, $beg + 1);
			$uid = str_replace("(", "", $uid);
			$uid = str_replace(")", "", $uid);
			$username = substr($details, 0, $beg);
		}
	}

	$time = get_tag(str_replace('class="otherdetails" ', '', $data), "span", "comment_otherdetails_$cid");
	if (substr($time, 0, 3) == "on ") {
		$time = substr($time, 3);
	}
	$beg = strpos($time, " ");
	$end = strpos($time, "(<");
	if ($beg !== false && $end !== false) {
		$time = substr($time, $beg + 1, $end - $beg - 2);
	}
	$time = trim($time);
	$time = str_replace("@", "", $time);
	$beg = strrpos($time, "on ");
	if ($beg !== false) {
		$time = substr($time, $beg + 3);
	}
	//print "time [$time]\n";
	$time = strtotime($time);

	$body = get_tag($data, "div", "comment_body_$cid");
	$body = str_replace("</p>", "</p><br><br>", $body);
	$body = clean_html($body, "comment");
	if (substr($body, -10) == "<br/><br/>") {
		$body = substr($body, 0, -10);
	}

	$end = strpos($data, '<div class="comment_footer">');
	if ($end !== false) {
		$s = substr($data, 0, $end);
	} else {
		$s = $data;
	}
	$end = strrpos($s, '">Parent</a>');
	if ($end !== false) {
		$s = substr($s, 0, $end);
		$beg = strrpos($s, "?");
		if ($beg !== false) {
			$s = substr($s, $beg + 1);
			$s = str_replace("&amp;", "&", $s);
			$map = map_from_url_string($s);
			if (array_key_exists("cid", $map)) {
				$parent = $map["cid"];
			}
		}
	}

	//print "cid [$cid]\nsid [$sid]\nparent [$parent]\nuid [$uid]\nusername [$username]\nsubject [$subject]\nscore [$score]\nrating [$rating]\ntime [$time] [" . date("Y-m-d H:i", $time) . "]\nbody [$body]\n";
	//print "data [$data]\n";

	$zid = add_user($uid, $username);

	$flat_server = str_replace(".", "_", $server_name);
	$flat_server = str_replace("-", "_", $flat_server);
	$flat_import = str_replace(".", "_", $import_server_name);
	$flat_import = str_replace("-", "_", $flat_import);

	if (db_has_rec("soylentnews_comment", $cid)) {
		$soylentnews_comment = db_get_rec("soylentnews_comment", $cid);
		if ($soylentnews_comment["comment_id"] == "") {
			$comment_id = create_id($zid, $time);
			$comment_id = str_replace($flat_server, $flat_import, $comment_id);
			$comment = array();
			$comment["comment_id"] = $comment_id;
			$comment["short_id"] = "";
		} else {
			$comment = db_get_rec("comment", $soylentnews_comment["comment_id"]);
		}
	} else {
		$comment_id = create_id($zid, $time);
		$comment_id = str_replace($flat_server, $flat_import, $comment_id);
		$comment = array();
		$comment["comment_id"] = $comment_id;
		$comment["short_id"] = "";
		$soylentnews_comment = array();
		$soylentnews_comment["cid"] = $cid;
		$soylentnews_comment["sid"] = $sid;
		$soylentnews_comment["comment_id"] = $comment_id;
	}

	$soylentnews_comment["body"] = $body;
	$soylentnews_comment["last_sync"] = $now;
	$soylentnews_comment["parent"] = $parent;
	$soylentnews_comment["rating"] = $rating;
	$soylentnews_comment["score"] = $score;
	$soylentnews_comment["subject"] = $subject;
	$soylentnews_comment["time"] = $time;
	$soylentnews_comment["uid"] = $uid;

	$comment["body"] = $body;
	$comment["subject"] = $subject;
	$comment["time"] = $time;
	if ($parent > 0) {
		if (db_has_rec("soylentnews_comment", $parent)) {
			$parent_comment = db_get_rec("soylentnews_comment", $parent);
			$comment["parent_id"] = $parent_comment["comment_id"];
		} else {
			$comment["parent_id"] = "FIXME";
		}
	} else {
		$comment["parent_id"] = "";
	}
	if (db_has_rec("soylentnews_story", $sid)) {
		$soylentnews_story = db_get_rec("soylentnews_story", $sid);
		$comment["root_id"] = $soylentnews_story["story_id"];
	} else {
		$comment["root_id"] = "FIXME";
	}
	$comment["type"] = "story";
	$comment["zid"] = $zid;

	if ($comment["root_id"] == "FIXME" || $comment["parent_id"] == "FIXME") {
		$soylentnews_comment["comment_id"] = "";
	} else {
		if ($comment["short_id"] == "") {
			$comment["short_id"] = create_short("comment", $comment["comment_id"]);
		}
		db_set_rec("comment", $comment);
	}
	db_set_rec("soylentnews_comment", $soylentnews_comment);

	$comment_vote = array();
	$comment_vote["zid"] = "soylent-news@$import_server_name";
	$comment_vote["comment_id"] = $comment["comment_id"];
	$comment_vote["reason"] = $rating;
	$comment_vote["time"] = $now;
	$comment_vote["value"] = $score;
	db_set_rec("comment_vote", $comment_vote);

	$soylentnews_log = array();
	$soylentnews_log["time"] = $now;
	$soylentnews_log["type"] = "comment";
	$soylentnews_log["item_id"] = $comment["comment_id"];
	//db_set_rec("soylentnews_log", $soylentnews_log);
	sql("insert into soylentnews_log (time, type, item_id) values (?, ?, ?)", $now, "comment", $comment["comment_id"]);
}


function fix_comments()
{
	$count = 0;

	$row = sql("select * from soylentnews_comment where comment_id = ''");
	for ($i = 0; $i < count($row); $i++) {
		$cid = $row[$i]["cid"];
		$sid = $row[$i]["sid"];
		$parent = $row[$i]["parent"];
		$comment_id = "";
		$zid = "";

		if ($parent == 0) {
			$parent_id = "";
		} else {
			if (db_has_rec("soylentnews_comment", $parent)) {
				$parent_comment = db_get_rec("soylentnews_comment", $parent);
				$parent_id = $parent_comment["comment_id"];
			} else {
				$parent_id = "";
			}
		}
		if (db_has_rec("soylentnews_story", $sid)) {
			$soylentnews_story = db_get_rec("soylentnews_story", $sid);
			$story_id = $soylentnews_story["story_id"];
		} else {
			$story_id = "";
		}

		if (($parent == 0 || $parent_id != "") && $story_id != "") {
			$fix = "yes";
			$zid = get_zid_from_uid($row[$i]["uid"]);

			$comment_id = create_id($zid, $row[$i]["time"]);;
			$comment = array();
			$comment["comment_id"] = $comment_id;
			$comment["body"] = $row[$i]["body"];
			$comment["short_id"] = create_short("comment", $comment["comment_id"]);
			$comment["subject"] = $row[$i]["subject"];
			$comment["time"] = $row[$i]["time"];
			$comment["parent_id"] = $parent_id;
			$comment["root_id"] = $story_id;
			$comment["type"] = "story";
			$comment["zid"] = $zid;
			db_set_rec("comment", $comment);

			$soylentnews_comment = db_get_rec("soylentnews_comment", $cid);
			$soylentnews_comment["comment_id"] = $comment_id;
			db_set_rec("soylentnews_comment", $soylentnews_comment);

			print "cid [$cid] comment_id [$comment_id] sid [$sid] parent [$parent] parent_id [$parent_id] story_id [$story_id] zid [$zid] fix [$fix]\n";
			$count++;
		} else {
			$fix = "no";
		}
	}

	return $count;
}
