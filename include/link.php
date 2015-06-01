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


function item_link($type_id, $short_id, $item = "")
{
	global $protocol;
	global $server_name;

	$short_code = crypt_crockford_encode($short_id);

	if ($type_id == 0) {
		$short = db_get_rec("short", $short_id);
		$type_id = $short["type_id"];
	}
	$type = item_type($type_id);

	if (item_user_based($type_id)) {
		if (!is_array($item)) {
			$item = db_get_rec($type, $short_id);
		}
		$link = user_link($item["zid"]) . $type;
	} else {
		$link = "$protocol://$server_name/$type";
	}

	if (is_string($item) && $item != "") {
		return "$link/$short_code/$item";
	}

	if (item_date_based($type_id)) {
		if (!is_array($item)) {
			$item = db_get_rec($type, $short_id);
		}
		if ($type == "journal") {
			if (!$item["published"]) {
				return "$link/$short_code";
			}
		}
		return "$link/" . gmdate("Y-m-d", $item["publish_time"]) . "/" . $item["slug"];
	}

	if (item_slug_based($type_id)) {
		if (!is_array($item)) {
			$item = db_get_rec($type, $short_id);
		}
		return "$link/" . $item["slug"];
	}

	return "$link/$short_code";
}


function item_redirect($type_id, $short_id, $item = "")
{
	header("Location: " . item_link($type_id, $short_id, $item));
	die();
}


function item_date_based($type_id)
{
	return in_array($type_id, [TYPE_JOURNAL, TYPE_POLL, TYPE_STORY]);
}


function item_user_based($type_id)
{
	return in_array($type_id, [TYPE_JOURNAL]);
}


function item_slug_based($type_id)
{
	return in_array($type_id, [TYPE_FEED, TYPE_JOURNAL, TYPE_POLL, TYPE_READER, TYPE_STORY]);
}


function item_request($type_id = 0)
{
	global $s2;
	global $s3;
	global $auth_zid;

	if (item_date_based($type_id) && string_uses($s2, "[0-9]-") && string_uses($s3, "[a-z][0-9]-") && string_has($s2, "-")) {
		$date = $s2;
		$slug = $s3;
		$time_beg = strtotime("$date GMT");
		if ($time_beg === false) {
			die("invalid date [$date]");
		}
		$time_end = $time_beg + DAYS;

		$type = item_type($type_id);
		$row = sql("select {$type}_id from $type where publish_time > ? and publish_time < ? and slug = ? order by publish_time", $time_beg, $time_end, $slug);
		if (count($row) == 0) {
			if ($type_id == TYPE_STORY) {
				$row = sql("select story.story_id from story inner join story_edit on story.story_id = story_edit.story_id where publish_time > ? and publish_time < ? and story_edit.slug = ?", $time_beg, $time_end, $slug);
				if (count($row) > 0) {
					header("Location: " . item_link(TYPE_STORY, $row[0]["story_id"]));
					die();
				}
			}
			header("Location: ./");
			die();
		}
		$short_id = $row[0]["{$type}_id"];
		$short_code = crypt_crockford_encode($short_id);
	} else if ($type_id == TYPE_FEED) {
		if (string_uses($s2, "[A-Z][0-9]")) {
			$feed_code = $s2;
			$feed_id = crypt_crockford_decode($feed_code);
			$feed = db_find_rec("feed", $feed_id);
		} else if (string_uses($s2, "[a-z][0-9]-")) {
			$slug = $s2;
			$feed = db_find_rec("feed", array("slug" => $slug));
		} else {
			die("invalid request [$s2]");
		}
		if ($feed === false) {
			die("unknown feed [$s2]");
		}

		return $feed;
	} else if ($type_id == TYPE_READER) {
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
	} else if ($type_id == TYPE_READER_TOPIC) {
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
	} else if ($type_id == TYPE_ARTICLE || $type_id == TYPE_THUMB) {
		$short_code = $s2;
		if (!string_uses($short_code, "[A-Z][a-z][0-9]")) {
			die("invalid short code [$short_code]");
		}
		$short_id = crypt_crockford_decode($short_code);
		$type = item_type($type_id);

		return db_get_rec($type, $short_id);
	} else {
		if ($type_id == TYPE_BUG_FILE) {
			$short_code = $s3;
		} else {
			$short_code = $s2;
		}
		if (!string_uses($short_code, "[A-Z][a-z][0-9]")) {
			die("invalid short code [$short_code]");
		}
		$short_id = crypt_crockford_decode($short_code);

		if ($type_id == 0) {
			$short = db_get_rec("short", $short_id);
			$type_id = $short["type_id"];
		}
	}

	$type = item_type($type_id);
	$rec = db_get_rec($type, $short_id);
	$rec["short_id"] = $short_id;
	$rec["short_code"] = $short_code;
	$rec["short_type_id"] = $type_id;
	$rec["short_type"] = item_type($type_id);

	return $rec;
}


function item_type($type_id)
{
	switch ($type_id) {
		// article
		case TYPE_UNKNOWN:
			return "unknown";
		case TYPE_ARTICLE:
			return "article";
		case TYPE_BODY:
			return "body";
		case TYPE_COMMENT:
			return "comment";
		case TYPE_COMMENT_VOTE:
			return "comment_vote";
		case TYPE_VOTE:
			return "vote";

		// ask
		case TYPE_ANSWER:
			return "answer";
		case TYPE_QUESTION:
			return "question";

		// bug
		case TYPE_BUG:
			return "bug";
		case TYPE_BUG_FILE:
			return "bug_file";

		// calendar
		case TYPE_APPOINTMENT:
			return "appointment";
		case TYPE_CALENDAR:
			return "calendar";

		// drive
		case TYPE_CACHE:
			return "cache";
		case TYPE_DRIVE_DIR:
			return "drive_dir";
		case TYPE_DRIVE_FILE:
			return "drive_file";

		// feed
		case TYPE_FEED:
			return "feed";
		case TYPE_FEED_TOPIC:
			return "feed_topic";

		// image
		case TYPE_GALLERY:
			return "gallery";
		case TYPE_IMAGE:
			return "image";
		case TYPE_PHOTO:
			return "photo";
		case TYPE_SCREENSHOT:
			return "screenshot";
		case TYPE_THUMB:
			return "thumb";

		// journal
		case TYPE_JOURNAL:
			return "journal";
		case TYPE_JOURNAL_TOPIC:
			return "journal_topic";

		// mail
		case TYPE_ADDRESS_BOOK:
			return "address_book";
		case TYPE_CONTACT:
			return "contact";
		case TYPE_LIST:
			return "list";
		case TYPE_MAIL:
			return "mail";
		case TYPE_MAIL_ATTACHMENT:
			return "mail_attachment";
		case TYPE_MAIL_BODY:
			return "mail_body";
		case TYPE_MAIL_SIGNATURE:
			return "mail_signature";

		// music
		case TYPE_ALBUM:
			return "album";
		case TYPE_COVER:
			return "cover";
		case TYPE_GENRE:
			return "genre";
		case TYPE_PLAYLIST:
			return "playlist";
		case TYPE_SONG:
			return "song";

		// news
		case TYPE_NEWS:
			return "news";
		case TYPE_NEWS_GROUP:
			return "news_group";

		// organization
		case TYPE_ORGANIZATION:
			return "organization";

		// poll
		case TYPE_POLL:
			return "poll";
		case TYPE_POLL_ANSWER:
			return "poll_answer";

		// project
		case TYPE_PROJECT:
			return "project";
		case TYPE_PROJECT_FILE:
			return "project_file";
		case TYPE_PROJECT_MILESTONE:
			return "project_milestone";
		case TYPE_PROJECT_RELEASE:
			return "project_release";

		// reader
		case TYPE_READER:
			return "reader";
		case TYPE_READER_TOPIC:
			return "reader_topic";

		// store
		case TYPE_STORE_ANSWER:
			return "store_answer";
		case TYPE_STORE_CART:
			return "store_cart";
		case TYPE_STORE_CATEGORY:
			return "store_category";
		case TYPE_STORE_FEATURE:
			return "store_feature";
		case TYPE_STORE_GALLERY:
			return "store_gallery";
		case TYPE_STORE_ITEM:
			return "store_item";
		case TYPE_STORE_QUESTION:
			return "store_question";
		case TYPE_STORE_REVIEW:
			return "store_review";

		// story
		case TYPE_PIPE:
			return "pipe";
		case TYPE_STORY:
			return "story";
		case TYPE_STORY_TOPIC:
			return "story_topic";

		// stream
		case TYPE_CARD:
			return "card";

		// user
		case TYPE_AVATAR:
			return "avatar";
		case TYPE_PRIVATE_KEY:
			return "private_key";
		case TYPE_PUBLIC_KEY:
			return "public_key";
		case TYPE_USER:
			return "user";

		// video
		case TYPE_VIDEO:
			return "video";

		default:
			die("unknown type [$type_id]");
	}
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
		$url = $protocol . "://" . zid_to_domain($zid) . ($trail ? "/" : "");
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


function zid_to_domain($zid)
{
	return str_replace("@", ".", $zid);
}


function domain_to_zid($domain)
{
	$pos = strpos($domain, ".");
	if (!string_uses($domain, "[a-z][0-9].-") || $pos === false) {
		die("invalid domain [$domain]");
	}

	return substr_replace($domain, "@", $pos, 1);
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

		header("Location: " . item_link($short["type_id"], $short_id));
		die();
	}
	die("unknown short_code [$short_code]");
}
