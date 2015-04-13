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


function item_link($type, $short_id, $item = "")
{
	global $protocol;
	global $server_name;

	$short_code = crypt_crockford_encode($short_id);

	if ($type == "") {
		$short = db_get_rec("short", $short_id);
		$type = $short["type"];
	}

	if (item_user_based($type)) {
		if (!is_array($item)) {
			$item = db_get_rec($type, $short_id);
		}
		$link = user_link($item["zid"]) . $type . "/";
	} else {
		$link = "$protocol://$server_name/$type";
	}

	if (is_string($item) && $item != "") {
		return "$link/$short_code/$item";
	}

	if (item_date_based($type)) {
		if (!is_array($item)) {
			$item = db_get_rec($type, $short_id);
		}
		return "$link/" . gmdate("Y-m-d", $item["publish_time"]) . "/" . $item["slug"];
	}

	if (item_slug_based($type)) {
		if (!is_array($item)) {
			$item = db_get_rec($type, $short_id);
		}
		return "$link/" . $item["slug"];
	}

	return "$link/$short_code";
}


function item_redirect($type, $short_id, $item = "")
{
	header("Location: " . item_link($type, $short_id, $item));
	die();
}


function item_date_based($type)
{
	return in_array($type, ["journal", "poll", "story"]);
}


function item_user_based($type)
{
	return in_array($type, ["journal"]);
}


function item_slug_based($type)
{
	return in_array($type, ["feed", "journal", "poll", "reader", "story"]);
}


function item_request($type = "")
{
	global $s2;
	global $s3;
	global $auth_zid;

	if (item_date_based($type) && string_uses($s2, "[0-9]-") && string_uses($s3, "[a-z][0-9]-") && string_has($s2, "-")) {
		$date = $s2;
		$slug = $s3;
		$time_beg = strtotime("$date GMT");
		if ($time_beg === false) {
			die("invalid date [$date]");
		}
		$time_end = $time_beg + 86400;

		$row = sql("select {$type}_id from $type where publish_time > ? and publish_time < ? and slug = ? order by publish_time", $time_beg, $time_end, $slug);
		if (count($row) == 0) {
			if ($type == "story") {
				$row = sql("select story.story_id from story inner join story_edit on story.story_id = story_edit.story_id where publish_time > ? and publish_time < ? and story_edit.slug = ?", $time_beg, $time_end, $slug);
				if (count($row) > 0) {
					header("Location: " . item_link("story", $row[0]["story_id"]));
					die();
				}
			}
			header("Location: ./");
			die();
		}
		$short_id = $row[0]["{$type}_id"];
		$short_code = crypt_crockford_encode($short_id);
	} else if ($type == "feed") {
		if (string_uses($s2, "[A-Z][0-9]")) {
			$feed_code = $s2;
			$feed_id = crypt_crockford_decode($feed_code);
			$feed = db_find_rec("feed", $feed_id);
		} else if (string_uses($s2, "[a-z][0-9]-")) {
			$slug = $s2;
			$feed = db_find_rec("feed", array("slug" => $slug));
			//if ($feed === false) {
			//	$feed = db_find_rec("feed_topic", ["slug" => $slug]);
			//	if ($feed === false) {
			//		die("unknown feed [$slug]");
			//	}
			//	$feed["short_type"] = "feed_topic";
			//} else {
			//	$feed["short_type"] = "feed";
			//}
		} else {
			die("invalid request [$s2]");
		}
		if ($feed === false) {
			die("unknown feed [$s2]");
		}

		return $feed;
	} else if ($type == "reader") {
		if (!string_uses($s2, "[a-z][0-9]-")) {
			die("invalid slug [$s2]");
		}
		if ($auth_zid === "") {
			die("please sign in");
		}
		$slug = $s2;
		$reader_user = db_find_rec("reader_user", ["zid" => $auth_zid, "slug" => $slug]);
		if ($reader_user === false) {
			die("unknown feed [$slug]");
		}

		return $reader_user;
	} else if ($type == "reader_topic") {
		if (!string_uses($s2, "[a-z][0-9]-")) {
			die("invalid slug [$s2]");
		}
		if ($auth_zid === "") {
			die("please sign in");
		}
		$slug = $s3;
		$reader_topic = db_find_rec("reader_topic", ["zid" => $auth_zid, "slug" => $slug]);
		if ($reader_topic === false) {
			die("unknown topic [$slug]");
		}

		return $reader_topic;
	} else if ($type == "article" || $type == "thumb") {
		$short_code = $s2;
		if (!string_uses($short_code, "[A-Z][a-z][0-9]")) {
			die("invalid short code [$short_code]");
		}
		$short_id = crypt_crockford_decode($short_code);

		return db_get_rec($type, $short_id);
	} else {
		if ($type == "bug_file") {
			$short_code = $s3;
		} else {
			$short_code = $s2;
		}
		if (!string_uses($short_code, "[A-Z][a-z][0-9]")) {
			die("invalid short code [$short_code]");
		}
		$short_id = crypt_crockford_decode($short_code);

		if ($type === "") {
			$short = db_get_rec("short", $short_id);
			$type = $short["type"];
		}
	}

	$rec = db_get_rec($type, $short_id);
	$rec["short_id"] = $short_id;
	$rec["short_code"] = $short_code;
	$rec["short_type"] = $type;

	return $rec;
}


function user_link($zid, $a = [])
{
	global $protocol;

	$tag = false;		// wrap link in html tags
	$ac = true;		// name a blank zid "Anonymous Coward"
	$author = false;	// include rel="author" in the tag
	$trail = true;		// include trailing slash
	extract($a);

	if ($ac && $zid == "") {
		$name = "Anonymous Coward";
		$url = "";
	} else {
		$name = $zid;
		$url = $protocol . "://" . str_replace("@", ".", $zid) . ($trail ? "/" : "");
	}
	$text = $name;
	if ($tag) {
		if ($url == "") {
			return $text;
		} else {
			if ($author) {
				return "<a href=\"$url\" rel=\"author\">$text</a>";
			} else {
				return "<a href=\"$url\">$text</a>";
			}
		}
	} else {
		return $url;
	}
}


function short_redirect($short_code)
{
	global $auth_zid;
	global $remote_ip;

	$short_id = crypt_crockford_decode($short_code);
	if (db_has_rec("short", $short_id)) {
		$short = db_get_rec("short", $short_id);

		$short_view = db_new_rec("short_view");
		$short_view["short_id"] = $short_id;
		if (empty($_SERVER["HTTP_USER_AGENT"])) {
			$short_view["agent"] = "";
		} else {
			$short_view["agent"] = $_SERVER["HTTP_USER_AGENT"];
		}
		if (empty($_SERVER["HTTP_REFERER"])) {
			$short_view["referer"] = "";
		} else {
			$short_view["referer"] = $_SERVER["HTTP_REFERER"];
		}
		$short_view["remote_ip"] = $remote_ip;
		$short_view["zid"] = $auth_zid;
		$short_view["time"] = time();
		db_set_rec("short_view", $short_view);

		header("Location: " . item_link($short["type"], $short_id));
		die();
	}
}
