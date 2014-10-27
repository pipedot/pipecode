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

$doc_root = substr(dirname(__FILE__), 0, -8);

set_include_path("$doc_root/include");

include("$doc_root/lib/tools/tools.php");
if (fs_is_file("$doc_root/conf.php")) {
	include("$doc_root/conf.php");
} else {
	include("$doc_root/setup.php");
	die();
}

$now = time();
$year = gmdate("Y");

$db_table["bug"] = array(
	array("name" => "bug_id", "key" => true),
	array("name" => "author_zid"),
	array("name" => "body"),
	array("name" => "closed", "default" => 0),
	array("name" => "closed_zid"),
	array("name" => "priority"),
	array("name" => "publish_time", "default" => $now),
	array("name" => "short_id", "default" => 0),
	array("name" => "title")
);

$db_table["bug_file"] = array(
	array("name" => "short_id", "key" => true, "default" => 0),
	array("name" => "long_id"),
	array("name" => "bug_short_id", "default" => 0),
	array("name" => "hash"),
	array("name" => "name"),
	array("name" => "remote_ip"),
	array("name" => "server"),
	array("name" => "size", "default" => 0),
	array("name" => "time", "default" => $now),
	array("name" => "type"),
	array("name" => "zid")
);

$db_table["bug_label"] = array(
	array("name" => "label_id", "key" => true, "auto" => true),
	array("name" => "label_name"),
	array("name" => "label_tag"),
	array("name" => "background_color"),
	array("name" => "foreground_color"),
	array("name" => "reportable", "default" => 0)
);

$db_table["bug_labels"] = array(
	array("name" => "bug_short_id", "default" => 0),
	array("name" => "label_id", "default" => 0)
);

$db_table["bug_view"] = array(
	array("name" => "bug_id", "key" => true),
	array("name" => "zid", "key" => true),
	array("name" => "time", "default" => 0),
	array("name" => "last_time", "default" => 0)
);

$db_table["captcha"] = array(
	array("name" => "captcha_id", "auto" => true, "key" => true),
	array("name" => "question"),
	array("name" => "answer")
);

$db_table["captcha_challenge"] = array(
	array("name" => "remote_ip", "key" => true),
	array("name" => "captcha_id", "default" => 0)
);

$db_table["card"] = array(
	array("name" => "short_id", "key" => true),
	array("name" => "card_id"),
	array("name" => "body"),
	array("name" => "edit_time", "default" => $now),
	array("name" => "image_id", "default" => 0),
	array("name" => "link_subject"),
	array("name" => "link_url"),
	array("name" => "photo_short_id", "default" => 0),
	array("name" => "publish_time", "default" => $now),
	array("name" => "zid"),
);

$db_table["card_edit"] = array(
	array("name" => "card_id", "key" => true),
	array("name" => "edit_time", "key" => true, "default" => $now),
	array("name" => "body")
);

$db_table["card_tags"] = array(
	array("name" => "short_id", "key" => true),
	array("name" => "tag", "key" => true)
);

$db_table["card_view"] = array(
	array("name" => "card_id", "key" => true),
	array("name" => "zid", "key" => true),
	array("name" => "time", "default" => $now),
	array("name" => "last_time", "default" => 0)
);

$db_table["card_vote"] = array(
	array("name" => "card_id", "key" => true),
	array("name" => "zid", "key" => true),
	array("name" => "value", "default" => 0)
);

$db_table["comment"] = array(
	array("name" => "comment_id", "key" => true),
	array("name" => "body"),
	array("name" => "edit_time", "default" => $now),
	array("name" => "parent_id"),
	array("name" => "publish_time", "default" => $now),
	array("name" => "root_id"),
	array("name" => "short_id", "default" => 0),
	array("name" => "subject"),
	array("name" => "type"),
	array("name" => "zid")
);

$db_table["comment_edit"] = array(
	array("name" => "comment_id", "key" => true),
	array("name" => "edit_time", "key" => true, "default" => $now),
	array("name" => "body"),
	array("name" => "subject")
);

$db_table["comment_vote"] = array(
	array("name" => "comment_id", "key" => true),
	array("name" => "zid", "key" => true),
	array("name" => "reason"),
	array("name" => "time", "default" => $now),
	array("name" => "value", "default" => 0)
);

$db_table["default_conf"] = array(
	array("name" => "conf", "key" => true),
	array("name" => "name", "key" => true),
	array("name" => "value")
);

$db_table["email_challenge"] = array(
	array("name" => "challenge", "key" => true),
	array("name" => "username"),
	array("name" => "email"),
	array("name" => "expires", "default" => $now + 86400 * 3)
);

$db_table["feed"] = array(
	array("name" => "fid", "auto" => true, "key" => true),
	array("name" => "time", "default" => $now),
	array("name" => "uri"),
	array("name" => "title"),
	array("name" => "link")
);

$db_table["feed_item"] = array(
	array("name" => "fid", "key" => true),
	array("name" => "time", "key" => true, "default" => $now),
	array("name" => "title"),
	array("name" => "link")
);

$db_table["feed_user"] = array(
	array("name" => "zid", "key" => true),
	array("name" => "fid", "key" => true),
	array("name" => "col", "default" => 0),
	array("name" => "pos", "default" => 0)
);

$db_table["image"] = array(
	array("name" => "image_id", "key" => true),
	array("name" => "hash"),
	array("name" => "original_width", "default" => 0),
	array("name" => "original_height", "default" => 0),
	array("name" => "original_url"),
	array("name" => "parent_url"),
	array("name" => "server"),
	array("name" => "time", "default" => $now),
	array("name" => "zid")
);

$db_table["journal"] = array(
	array("name" => "journal_id", "key" => true),
	array("name" => "body"),
	array("name" => "edit_time", "default" => $now),
	array("name" => "photo_id", "default" => 0),
	array("name" => "publish_time", "default" => 0),
	array("name" => "published", "default" => 0),
	array("name" => "short_id", "default" => 0),
	array("name" => "slug"),
	array("name" => "title"),
	array("name" => "topic"),
	array("name" => "zid")
);

$db_table["journal_photo"] = array(
	array("name" => "journal_short_id", "key" => true, "default" => 0),
	array("name" => "photo_short_id", "key" => true, "default" => 0)
);

$db_table["journal_view"] = array(
	array("name" => "journal_id", "key" => true),
	array("name" => "zid", "key" => true),
	array("name" => "time", "default" => 0),
	array("name" => "last_time", "default" => 0)
);

$db_table["mail"] = array(
	array("name" => "mail_id", "auto" => true, "key" => true),
	array("name" => "body"),
	array("name" => "in_reply_to"),
	array("name" => "location"),
	array("name" => "mail_from"),
	array("name" => "message_id"),
	array("name" => "rcpt_to"),
	array("name" => "received_time", "default" => $now),
	array("name" => "reply_to"),
	array("name" => "size", "default" => 0),
	array("name" => "subject"),
	array("name" => "zid")
);

$db_table["page"] = array(
	array("name" => "slug", "key" => true),
	array("name" => "title"),
	array("name" => "body")
);

$db_table["photo"] = array(
	array("name" => "short_id", "key" => true, "default" => 0),
	array("name" => "photo_id"),
	array("name" => "aspect_width", "default" => 0),
	array("name" => "aspect_height", "default" => 0),
	array("name" => "has_medium", "default" => 0),
	array("name" => "has_large", "default" => 0),
	array("name" => "hash"),
	array("name" => "original_name", "default" => 0),
	array("name" => "original_width", "default" => 0),
	array("name" => "original_height", "default" => 0),
	array("name" => "server"),
	array("name" => "size", "default" => 0),
	array("name" => "time", "default" => $now),
	array("name" => "zid")
);

$db_table["pipe"] = array(
	array("name" => "pipe_id", "key" => true),
	array("name" => "author_zid"),
	array("name" => "body"),
	array("name" => "closed", "default" => 0),
	array("name" => "edit_zid"),
	array("name" => "icon"),
	array("name" => "reason"),
	array("name" => "short_id", "default" => 0),
	array("name" => "slug"),
	array("name" => "tid", "default" => 0),
	array("name" => "time", "default" => $now),
	array("name" => "title")
);

$db_table["pipe_view"] = array(
	array("name" => "pipe_id", "key" => true),
	array("name" => "zid", "key" => true),
	array("name" => "time", "default" => 0),
	array("name" => "last_time", "default" => 0)
);

$db_table["pipe_vote"] = array(
	array("name" => "pipe_id", "key" => true),
	array("name" => "zid", "key" => true),
	array("name" => "time", "default" => $now),
	array("name" => "value", "default" => 0)
);

$db_table["poll_answer"] = array(
	array("name" => "answer_id", "key" => true),
	array("name" => "poll_id"),
	array("name" => "answer"),
	array("name" => "position", "default" => 0)
);

$db_table["poll"] = array(
	array("name" => "poll_id", "key" => true),
	array("name" => "publish_time", "default" => $now),
	array("name" => "question"),
	array("name" => "short_id", "default" => 0),
	array("name" => "slug"),
	array("name" => "type_id", "default" => 0),
	array("name" => "zid")
);

$db_table["poll_view"] = array(
	array("name" => "poll_id", "key" => true),
	array("name" => "zid", "key" => true),
	array("name" => "time", "default" => 0),
	array("name" => "last_time", "default" => 0)
);

$db_table["poll_vote"] = array(
	array("name" => "poll_id"),
	array("name" => "zid"),
	array("name" => "answer_id"),
	array("name" => "time", "default" => $now),
	array("name" => "points", "default" => 0)
);

$db_table["server_conf"] = array(
	array("name" => "name"),
	array("name" => "value")
);

$db_table["short"] = array(
	array("name" => "short_id", "auto" => true, "key" => true),
	array("name" => "type"),
	array("name" => "item_id")
);

$db_table["short_view"] = array(
	array("name" => "view_id", "auto" => true, "key" => true),
	array("name" => "short_id"),
	array("name" => "remote_ip"),
	array("name" => "time", "default" => $now),
	array("name" => "agent"),
	array("name" => "referer"),
	array("name" => "zid")
);

$db_table["story"] = array(
	array("name" => "story_id", "key" => true),
	array("name" => "author_zid"),
	array("name" => "body"),
	array("name" => "edit_time", "default" => $now),
	array("name" => "edit_zid"),
	array("name" => "icon"),
	array("name" => "image_id", "default" => 0),
	array("name" => "pipe_id"),
	array("name" => "publish_time", "default" => $now),
	array("name" => "short_id", "default" => 0),
	array("name" => "slug"),
	array("name" => "tid", "default" => 0),
	array("name" => "title"),
	array("name" => "tweet_id", "default" => 0)
);

$db_table["story_edit"] = array(
	array("name" => "story_id", "key" => true),
	array("name" => "edit_time", "key" => true, "default" => $now),
	array("name" => "body"),
	array("name" => "edit_zid"),
	array("name" => "icon"),
	array("name" => "image_id", "default" => 0),
	array("name" => "slug"),
	array("name" => "tid", "default" => 0),
	array("name" => "title")
);

$db_table["story_view"] = array(
	array("name" => "story_id", "key" => true),
	array("name" => "zid", "key" => true),
	array("name" => "time", "default" => $now),
	array("name" => "last_time", "default" => 0)
);

$db_table["tag"] = array(
	array("name" => "tag_id", "auto" => true, "key" => true),
	array("name" => "tag")
);

$db_table["tmp_image"] = array(
	array("name" => "tmp_image_id", "auto" => true, "key" => true),
	array("name" => "hash"),
	array("name" => "original_width", "default" => 0),
	array("name" => "original_height", "default" => 0),
	array("name" => "original_url"),
	array("name" => "parent_url"),
	array("name" => "server"),
	array("name" => "time", "default" => $now),
	array("name" => "zid")
);

$db_table["topic"] = array(
	array("name" => "tid", "auto" => true, "key" => true),
	array("name" => "icon"),
	array("name" => "promoted", "default" => 0),
	array("name" => "slug"),
	array("name" => "topic")
);

$db_table["user_conf"] = array(
	array("name" => "zid", "key" => true),
	array("name" => "name"),
	array("name" => "value")
);

$reasons["Normal"] = 0;
$reasons["Offtopic"] = -1;
$reasons["Flamebait"] = -1;
$reasons["Troll"] = -1;
$reasons["Redundant"] = -1;
$reasons["Insightful"] = 1;
$reasons["Interesting"] = 1;
$reasons["Informative"] = 1;
$reasons["Funny"] = 1;
$reasons["Overrated"] = -1;
$reasons["Underrated"] = 1;


function print_header($title = "", $link_name = array(), $link_icon = array(), $link_url = array())
{
	global $request_script;
	global $auth_zid;
	global $auth_user;
	global $user_page;
	global $server_title;
	global $server_name;
	global $https_enabled;
	global $request_script;
	global $protocol;
	global $request_script;
	global $doc_root;
	global $server_conf;

	header_expires();
	header_html();

	writeln('<!DOCTYPE html>');
	writeln('<html>');
	writeln('<head>');

	if ($title != "") {
		$title .= " - ";
	}
	if ($user_page == "") {
		$title .= $server_title;
	} else {
		$title .= $user_page . '.' . $server_name;
	}
	writeln('<title>' . $title . '</title>');
	writeln('<meta http-equiv="Content-type" content="text/html;charset=UTF-8">');
	writeln('<meta name="viewport" content="width=device-width, initial-scale=1">');
	$theme = $server_conf["theme"];
	writeln('<link rel="stylesheet" href="/theme/' . $theme . '/style.css?t=' . fs_time("$doc_root/www/theme/$theme/style.css") . '" type="text/css"/>');
	if ($request_script == "/") {
		writeln('<link rel="alternate" href="/atom" type="application/atom+xml" title="Stories">');
	}
	if ($auth_user["javascript_enabled"]) {
		writeln('<script type="text/javascript" src="/lib/jquery/jquery.js?t=' . fs_time("$doc_root/www/lib/jquery/jquery.js") . '"></script>');
		writeln('<script type="text/javascript" src="/lib/jquery/jquery-ui.js?t=' . fs_time("$doc_root/www/lib/jquery/jquery-ui.js") . '"></script>');
		writeln('<script type="text/javascript" src="/common.js?t=' . fs_time("$doc_root/www/common.js") . '"></script>');
	}

	writeln('</head>');
	writeln('<body>');

	writeln('<header>');
	writeln('<table class="title">');
	writeln('	<tr>');
	//if ($user_page == "") {
		//writeln('		<td><a href="' . $protocol . '://' . $server_name . '/"><img alt="' . $server_title . '" class="logo_large" src="' . $protocol . '://' . $server_name . '/images/logo-top.png"/><img alt="' . $server_title . '" class="logo_small" src="' . $protocol . '://' . $server_name . '/images/logo-64.png"/></a></td>');
		writeln('		<td><a href="' . $protocol . '://' . $server_name . '/"><div class="logo_big"></div><div class="logo_small"></div></a></td>');
	//} else {
	//	writeln('		<td><a href="' . $protocol . '://' . $server_name . '/"><img alt="' . $server_title . '" src="/images/logo-top.png"/></a></td>');
	//}

	if ($user_page === "") {
		if ($auth_zid === "") {
			if ($server_conf["submit_enabled"]) {
				$link_name[] = "Submit";
			}
			if ($server_conf["sign_up_enabled"]) {
				$link_name[] = "Sign Up";
			}
			$link_name[] = "Sign In";
		} else {
			if ($server_conf["submit_enabled"]) {
				$link_name[] = "Submit";
			}
			$link_name[] = "Home";
			if (($auth_user["admin"] || $auth_user["editor"]) && $request_script != "/menu/") {
				$link_name[] = "Tools";
			}
			$link_name[] = "Sign Out";
		}
	} else {
		if ($auth_zid === "") {
			$link_name[] = "Sign In";
		} else {
			if ($request_script != "/menu/") {
				$link_name[] = "Home";
			}
			$link_name[] = "Sign Out";
		}
	}

	writeln("		<td class=\"title_links\">");
	for ($i = 0; $i < count($link_name); $i++) {
		$name = $link_name[$i];
		$icon = "";
		$link = "";

		if ($name == "Submit") {
			$icon = "notepad";
			$link = "/submit";
		} else if ($name == "Home") {
			$icon = "home";
			$link = user_page_link($auth_zid) . "menu/";
		} else if ($name == "Tools") {
			$icon = "tools";
			$link = "/menu/";
		} else if ($name == "Sign Up") {
			$icon = "contact_new";
			$link = ($https_enabled ? "https" : $protocol ) . "://$server_name/sign_up";
		} else if ($name == "Sign In") {
			$icon = "users";
			$link = ($https_enabled ? "https" : $protocol ) . "://$server_name/sign_in";
		} else if ($name == "Sign Out") {
			$icon = "exit";
			$link = "$protocol://$server_name/sign_out";
		}

		if ($icon == "") {
			$icon = $link_icon[$i];
		}
		if ($link == "") {
			$link = $link_url[$i];
		}

		writeln("			<a href=\"$link\" class=\"icon_16 {$icon}_16\">$name</a>" . ($i == count($link_name) - 1 ? '' : ' | '));
	}
	writeln("		</td>");

	writeln('	</tr>');
	writeln('</table>');
	writeln('</header>');
}


function print_left_bar($type = "main", $selected = "stories")
{
	global $auth_zid;
	global $auth_user;
	global $server_name;
	global $zid;

	if ($type === "main") {
		if ($auth_zid === "") {
			$section_name = array("stories", "pipe", "poll", "search", "topics", "feed", "stream");
			$section_link = array("", "pipe/", "poll/", "search", "topic", "feed/", "stream/");
		} else {
			$section_name = array("stories", "pipe", "poll", "search", "topics", "stream");
			$section_link = array("", "pipe/", "poll/", "search", "topic", "stream/");
		}
	} else if ($type === "user") {
		if ($auth_zid === $zid) {
			$section_name = array("overview", "journal", "topics", "stream", "comments", "feed", "karma");
			$section_link = array("", "journal/", "topic", "stream/", "comments", "feed/", "karma/");
		} else {
			//$section_name = array("blog", "feed", "submissions", "comments", "achievements");
			//$section_link = array("blog", "feed", "submissions", "comments", "achievements");
			$section_name = array("overview", "journal", "stream", "comments", "feed", "karma");
			$section_link = array("", "journal/", "stream/", "comments", "feed/", "karma/");
		}
	}

	writeln('<nav>');
	for ($i = 0; $i < count($section_name); $i++) {
		$link = "/" . $section_link[$i];
		if ($selected == $section_name[$i]) {
			writeln('	<a class="nav_active" href="' . $link . '">' . $section_name[$i] . '</a>');
		} else {
			writeln('	<a class="nav_inactive" href="' . $link . '">' . $section_name[$i] . '</a>');
		}
	}

	if ($type == "main") {
		writeln('	<div class="topics">');
		writeln('	<hr/>');
		$list = db_get_list("topic", "topic", array("promoted" => 1));
		$keys = array_keys($list);
		for ($i = 0; $i < count($keys); $i++) {
			$topic = $list[$keys[$i]]["topic"];
			if ($topic == $selected) {
				writeln('	<a class="nav_active" href="/topic/' . $topic . '">' . $topic . '</a>');
			} else {
				writeln('	<a class="nav_inactive" href="/topic/' . $topic . '">' . $topic . '</a>');
			}
		}
		writeln('	</div>');
	} else if ($type == "user") {
		$row = sql("select distinct topic from journal where zid = ? order by topic", $zid);
		if (count($row) > 0) {
			writeln('	<div class="topics">');
			writeln('	<hr/>');
			for ($i = 0; $i < count($row); $i++) {
				$topic = $row[$i]["topic"];
				if ($topic == $selected) {
					writeln('	<a class="nav_active" href="/topic/' . $topic . '">' . $topic . '</a>');
				} else {
					writeln('	<a class="nav_inactive" href="/topic/' . $topic . '">' . $topic . '</a>');
				}
			}
			writeln('	</div>');
		}
	}
	writeln('</nav>');
}


function print_user_box()
{
	global $auth_zid;
	global $auth_user;
	global $server_name;
	global $protocol;

	$row = sql("select count(*) as mail_count from mail where zid = ? and location = 'Inbox'", $auth_zid);
	$mail_count = (int) $row[0]["mail_count"];
	if ($mail_count > 0) {
		$mail = "Mail ($mail_count)";
	} else {
		$mail = "Mail";
	}
	$link = user_page_link($auth_zid);

	writeln('<div class="dialog_title">' . $auth_zid . '</div>');
	writeln('<div class="dialog_body">');
	writeln('<table class="user_box">');
	writeln('	<tr>');
	//writeln('		<td><a href="' . $link . 'comments"><div class="chat_32">Comments</div></a></td>');
	writeln('		<td><a href="' . $link . 'feed/"><div class="news_32">Feed</div></a></td>');
	writeln('		<td><a href="' . $link . 'journal/"><div class="notepad_32">Journal</div></a></td>');
	writeln('	</tr>');
//	writeln('	<tr>');
//	writeln('		<td><a href="' . $link . 'karma/"><div class="user_box_icon" style="background-image: url(/images/karma-good-32.png)">Karma</div></a></td>');
//	writeln('		<td><a href="' . $link . '"><div class="user_box_icon" style="background-image: url(/images/news-32.png)">Feed</div></a></td>');
//	writeln('	</tr>');
//	writeln('	<tr>');
//	writeln('		<td><a href="' . $link . 'comments"><div class="user_box_icon" style="background-image: url(/images/chat-32.png)">Comments</div></a></td>');
//	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td><a href="' . $link . 'mail/"><div class="mail_32">' . $mail . '</div></a></td>');
	writeln('		<td><a href="' . $link . 'profile/"><div class="tools_32">Settings</div></a></td>');
	writeln('	</tr>');
//	writeln('	<tr>');
//	writeln('		<td><a href="' . $link . 'stream/"><div class="internet_32">Stream</div></a></td>');
//	writeln('		<td><a href="http://' . $auth_user["username"] . '.' . $server_name . '/friends/"><div class="user_box_icon" style="background-image: url(/images/users-32.png)">Friends</div></a></td>');
//	writeln('		<td><div class="user_box_icon"></div></td>');
//	writeln('	</tr>');
	writeln('</table>');
	writeln('</div>');
}


function beg_main($class = "")
{
	if ($class == "" || $class == "block") {
		writeln('<main>');
	} else if ($class == "stream") {
		writeln('<main class="stream">');
		writeln('<div id="container" style="margin: 0 auto">');
	} else {
		writeln("<main class=\"$class\">");
	}
}


function end_main($class = "")
{
	global $doc_root;

	if ($class == "stream") {
		writeln('</div>');
		writeln('</main>');

		writeln('<script src="/lib/masonry/masonry.js?' . fs_time("$doc_root/www/lib/masonry/masonry.js") . '"></script>');
		writeln('<script>');
		writeln('var container = document.querySelector("#container");');
		writeln('var msnry = new Masonry( container, {');
		writeln('	columnWidth: 346,');
		writeln('	"isFitWidth": true,');
		writeln('	itemSelector: ".card"');
		writeln('});');
		writeln('</script>');
	} else {
		writeln('</main>');
	}
}


function page_footer($table, $items_per_page, $where = array())
{
	$page = http_get_int("page", array("default" => 1, "required" => false));
	if (string_has($table, " ")) {
		$row = sql($table, $where);
	} else {
		$sql = "select count(*) as item_count from $table";
		if (count($where) > 0) {
			$k = array_keys($where);
			$sql .= " where ";
			$a = array();
			for ($i = 0; $i < count($where); $i++) {
				$sql .= $k[$i] . " = ? and ";
				$a[] = $where[$k[$i]];
			}
			$sql = substr($sql, 0, -5);
			$row = sql($sql, $a);
		} else {
			$row = sql($sql);
		}
	}
	$item_count = (int) $row[0]["item_count"];
	$pages_count = ceil($item_count / $items_per_page);
	$item_start = ($page - 1) * $items_per_page;

	$s = "";
	if ($page > 1) {
		$s .= "<a class=\"pages_left\" href=\"?page=" . ($page - 1) . "\" title=\"Back\"></a>";
	}
	if ($pages_count > 10) {
		if ($page > 5) {
			$s .= "...";
		}
		$start = $page - 4;
		if ($start < 1) {
			$start = 1;
		}
		$end = $start + 9;
		if ($end > $pages_count) {
			$end = $pages_count;
		}
		for ($i = $start; $i <= $end; $i++) {
			if ($i == $page) {
				$s .= "<span>$i</span>";
			} else {
				$s .= "<a href=\"?page=$i\">$i</a>";
			}
		}
		if ($end < $pages_count) {
			$s .= "...";
		}
	} else {
		for ($i = 1; $i <= $pages_count; $i++) {
			if ($i == $page) {
				$s .= "<span>$i</span>";
			} else {
				$s .= "<a href=\"?page=$i\">$i</a>";
			}
		}
	}
	if ($page < $pages_count) {
		$s .= "<a class=\"pages_right\" href=\"?page=" . ($page + 1) . "\" title=\"Next\"></a>";
	}

	return array($item_start, "<div class=\"pages\">$s</div>");
}


function print_footer()
{
	global $user_page;
	global $server_name;
	global $server_title;
	global $server_slogan;

	if ($user_page == "") {
		writeln('<footer class="footer">');
		writeln('<div>');
		writeln('	<a href="/about">About</a>');
		//writeln('	<a href="/bug/">Bugs</a>');
		writeln('	<a href="http://bugs.pipedot.org/">Bugs</a>');
		writeln('	<a href="/faq">FAQ</a>');
		writeln('	<a href="/atom" class="icon_16 feed_16">Feed</a>');
		//writeln('	<a href="/privacy">Privacy</a>');
		//writeln('	<a href="/terms">Terms</a>');
		writeln('	<a href="/source">Source</a>');
		writeln('</div>');
		writeln('<div>' . $server_title . ': ' . $server_slogan . '</div>');
		writeln('</footer>');
	} else {
		// user page footer
	}

	writeln('</body>');
	writeln('</html>');
}


function expire_auth()
{
	global $server_name;

	setcookie("auth", "", time() - (5 * 365 * 24 * 60 * 60), "/", ".$server_name");

	// XXX: kill old cookie
	setcookie("auth", "", time() - (5 * 365 * 24 * 60 * 60), "/");
}


function check_auth()
{
	global $auth_key;
	global $auth_zid;
	global $auth_user;
	global $request_script;

	$auth_zid = "";

	$auth = @$_COOKIE["auth"];
	$map = map_from_url_string($auth);
	$expire = @$map["expire"];
	$zid = @$map["zid"];
	$hash = @$map["hash"];

	$auth_user = db_get_conf("user_conf", $zid);

	if ($zid == "") {
		$auth_user["javascript_enabled"] = 0;
		return;
	}
	if (!string_uses($expire, "[0-9]")) {
		expire_auth();
		die("invalid expire");
	}
	if (time() > $expire) {
		expire_auth();
		die("auth expired");
	}
	if (!string_uses($zid, "[a-z][0-9]@.-")) {
		expire_auth();
		die("invalid zid [$zid]");

	}

	$test = crypt_sha256($auth_key . "expire=$expire&zid=$zid");
	if ($hash != $test) {
		expire_auth();
		die("wrong auth hash");
	}

	$auth_zid = $zid;
	//$auth_user = db_get_conf("user_conf", $auth_zid);
}


function clean_url($dirty)
{
	$dirty = str_replace("<b>", "", $dirty);
	$dirty = str_replace("</b>", "", $dirty);
	$dirty = str_replace("<i>", "", $dirty);
	$dirty = str_replace("</i>", "", $dirty);
	$dirty = str_replace("<s>", "", $dirty);
	$dirty = str_replace("</s>", "", $dirty);
	$dirty = str_replace("<q>", "", $dirty);
	$dirty = str_replace("</q>", "", $dirty);
	$dirty = str_replace("&amp;", "", $dirty);
	$dirty = str_replace("&lt;", "", $dirty);
	$dirty = str_replace("&gt;", "", $dirty);
	$dirty = str_replace("&quot;", "", $dirty);

	$clean = string_clean($dirty, "[a-z][A-Z][0-9]/- ");
	$clean = str_replace("/", " ", $clean);
	$clean = strtolower(trim($clean));
	$clean = str_replace(" ", "-", $clean);
	$clean = string_replace_all("--", "-", $clean);

	return $clean;
}


function article_info($comment, $force_https = true)
{
	global $server_name;
	global $protocol;

	if ($force_https) {
		$p = "https";
	} else {
		$p = $protocol;
	}
	$a = array();
	if ($comment["type"] == "story") {
		$story = db_get_rec("story", $comment["root_id"]);
		$a["type"] = "story";
		$a["title"] = $story["title"];
		$date = gmdate("Y-m-d", $story["publish_time"]);
		$a["link"] = "$p://$server_name/story/$date/" . $story["slug"];
	} else if ($comment["type"] == "pipe") {
		$pipe = db_get_rec("pipe", $comment["root_id"]);
		$a["type"] = "pipe";
		$a["title"] = $pipe["title"];
		$a["link"] = "$p://$server_name/pipe/" . crypt_crockford_encode($pipe["short_id"]);
	} else if ($comment["type"] == "poll") {
		$poll = db_get_rec("poll", $comment["root_id"]);
		$a["type"] = "poll";
		$a["title"] = $poll["question"];
		$a["link"] = "$p://$server_name/poll/" . gmdate("Y-m-d", $poll["time"]) . "/" . $poll["slug"];
	} else if ($comment["type"] == "journal") {
		$journal = db_get_rec("journal", $comment["root_id"]);
		$a["type"] = "journal";
		$a["title"] = $journal["title"];
		if ($journal["published"]) {
			$a["link"] = user_page_link($journal["zid"]) . "journal/" . gmdate("Y-m-d", $journal["publish_time"]) . "/" . $journal["slug"];
		} else {
			$a["link"] = user_page_link($journal["zid"]) . "journal/" . crypt_crockford_encode($journal["short_id"]);
		}
	} else if ($comment["type"] == "card") {
		$card = db_get_rec("card", array("card_id" => $comment["root_id"]));
		$a["type"] = "card";
		$a["title"] = "#" . crypt_crockford_encode($card["short_id"]);
		$a["link"] = "$p://$server_name/card/" . crypt_crockford_encode($card["short_id"]);
	}

	return $a;
}


function check_article_type($type)
{
	$a = array("card", "story", "pipe", "poll", "journal");
	if (!in_array($type, $a)) {
		die("unknown article type [$type]");
	}
}


function item_link($type, $item_id, $short_code = "")
{
	global $protocol;
	global $server_name;

	if ($type != "story" && $short_code == "") {
		$short = db_get_rec("short", array("item_id" => $item_id));
		$short_code = crypt_crockford_encode($short["short_id"]);
	}
	if ($type == "story") {
		$story = db_get_rec("story", $item_id);
		return "$protocol://$server_name/$type/" . gmdate("Y-m-d", $story["publish_time"]) . "/" . $story["slug"];
	} else if ($type == "pipe") {
		return "$protocol://$server_name/$type/$short_code";
	} else if ($type == "poll") {
		$poll = db_get_rec("poll", $item_id);
		return "$protocol://$server_name/$type/" . gmdate("Y-m-d", $poll["publish_time"]) . "/" . $poll["slug"];
	} else if ($type == "journal") {
		$journal = db_get_rec("journal", $item_id);
		if ($journal["publish_time"] > 0) {
			return user_page_link($journal["zid"]) . "$type/" . gmdate("Y-m-d", $journal["publish_time"]) . "/" . $journal["slug"];
		} else {
			return user_page_link($journal["zid"]) . "$type/$short_code";
		}
	} else if ($type == "user") {
		return user_page_link($item_id);
	} else if ($type == "topic") {
		return "$protocol://$server_name/topic/" . clean_url($item_id);
	} else {
		return "$protocol://$server_name/$type/$short_code";
	}
}


function print_noscript()
{
	global $server_name;
	global $auth_zid;
	global $auth_user;
	global $protocol;

	writeln('<noscript>');
	writeln('<div class="balloon">');
	writeln('<h1>JavaScript Disabled</h1>');
	writeln('<p>Which is fine! But you are currently browsing the JavaScript version of this page. Please do one of the following:</p>');
	writeln('<ul>');
	writeln('	<li>Enable scripts from <b>' . $server_name . '</b></li>');
	if ($auth_zid === "") {
		writeln('	<li>Sign in and uncheck the "Use JavaScript" option on your account settings page.</li>');
		//writeln('	<li>Click <a href="">this link</a> to get a cookie that disables JavaScript. (not working yet)</li>');
	} else {
		writeln('	<li>Uncheck the "Use JavaScript" option on your <a href="' . user_page_link($auth_zid) . 'profile/">account settings page</a>.</li>');
	}
	writeln('</ul>');
	writeln('</div>');
	writeln('</noscript>');
}


function karma_description($karma)
{
	if ($karma < -25) {
		return "Terrible";
	} else if ($karma < 0) {
		return "Bad";
	} else if ($karma == 0) {
		return "Neutral";
	} else if ($karma < 25) {
		return "Good";
	} else {
		return "Excellent";
	}
}


function create_id($zid, $time = 0)
{
	global $server_name;

	if ($zid == "") {
		$zid = "a_" . substr(crypt_sha256(rand() . microtime()), 0, 8) . "_$server_name";
	} else {
		$zid = str_replace("@", "_", $zid);
	}
	$zid = string_replace_all(".", "_", $zid);
	$zid = string_replace_all("-", "_", $zid);
	if ($time == 0) {
		$time = time();
	}

	return "{$time}_$zid";
}


function create_short($type, $item_id)
{
	if (!db_has_rec("short", array("type" => $type, "item_id" => $item_id))) {
		$short = array();
		$short["short_id"] = 0;
		$short["type"] = $type;
		$short["item_id"] = $item_id;
		db_set_rec("short", $short);
	}

	$short = db_get_rec("short", array("type" => $type, "item_id" => $item_id));
	return $short["short_id"];
}


function is_local_user($zid)
{
	$row = sql("select value from user_conf where zid = ? and name = 'password'", $zid);
	if (count($row) == 0) {
		return false;
	}

	return true;
}


function update_view_time($type, $root_id)
{
	global $auth_zid;

	if ($auth_zid === "") {
		$last_seen = 0;
	} else {
		if (db_has_rec("{$type}_view", array("{$type}_id" => $root_id, "zid" => $auth_zid))) {
			$view = db_get_rec("{$type}_view", array("{$type}_id" => $root_id, "zid" => $auth_zid));
			$view["last_time"] = $view["time"];
			$last_seen = $view["time"];
		} else {
			$view = array();
			$view["{$type}_id"] = $root_id;
			$view["zid"] = $auth_zid;
			$view["last_time"] = 0;
			$last_seen = 0;
		}
		$view["time"] = time();
		db_set_rec("{$type}_view", $view);
	}

	return $last_seen;
}


function revert_view_time($type, $root_id)
{
	global $auth_zid;

	if (db_has_rec("{$type}_view", array("{$type}_id" => $root_id, "zid" => $auth_zid))) {
		$view = db_get_rec("{$type}_view", array("{$type}_id" => $root_id, "zid" => $auth_zid));
		$view["time"] = $view["last_time"];
		db_set_rec("{$type}_view", $view);
	}
}


function count_comments($type, $root_id)
{
	global $auth_zid;

	$comments = array();
	$row = sql("select count(*) as comments from comment where type = ? and root_id = ?", $type, $root_id);
	$comments["count"] = $row[0]["comments"];
	if ($comments["count"] == 1) {
		$comments["label"] = " comment";
	} else {
		$comments["label"] = " comments";
	}

	if ($auth_zid === "") {
		$comments["new"] = 0;
	} else {
		$row = sql("select time from {$type}_view where {$type}_id = ? and zid = ?", $root_id, $auth_zid);
		if (count($row) > 0) {
		//if (db_has_rec("{$type}_view", array("{$type}_id" => $root_id, "zid" => $auth_zid))) {
			//$view = db_get_rec("{$type}_view", array("{$type}_id" => $root_id, "zid" => $auth_zid));
			$time = $row[0]["time"];
			$row = sql("select count(*) as comments from comment where type = ? and root_id = ? and edit_time > ?", $type, $root_id, $time);
			$comments["new"] = $row[0]["comments"];
		} else {
			$comments["new"] = $comments["count"];
		}
	}
	$comments["tag"] = "<b>" . $comments["count"] . "</b> " . $comments["label"];
	if ($comments["new"] > 0) {
		$comments["tag"] .= ", <b>" . $comments["new"] . "</b> new";
	}

	return $comments;
}


function find_rec($type = "")
{
	global $s2;
	global $s3;

	if (($type == "story" || $type == "poll" || $type == "journal") && string_uses($s2, "[0-9]-") && string_uses($s3, "[a-z][0-9]-")) {
		$date = $s2;
		$slug = $s3;
		$time_beg = strtotime("$date GMT");
		if ($time_beg === false) {
			die("invalid date [$date]");
		}
		$time_end = $time_beg + 86400;

		$row = sql("select short_id from $type where publish_time > ? and publish_time < ? and slug = ? order by publish_time", $time_beg, $time_end, $slug);
		if (count($row) == 0) {
			die("$type not found - date [$date] title [$slug]");
		}
		$short_id = $row[0]["short_id"];
		$short_code = crypt_crockford_encode($short_id);
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

	$rec = db_get_rec($type, array("short_id" => $short_id));
	$rec["short_code"] = $short_code;

	return $rec;
}


function print_comments($type, $rec)
{
	global $auth_zid;
	global $auth_user;
	global $can_moderate;
	global $hide_value;
	global $expand_value;

	if ($auth_zid !== "") {
		$can_moderate = true;
		$hide_value = $auth_user["hide_threshold"];
		$expand_value = $auth_user["expand_threshold"];
	} else {
		$can_moderate = false;
		$hide_value = -1;
		$expand_value = 0;
	}

	if ($auth_user["javascript_enabled"]) {
		print_sliders($type, $rec["{$type}_id"]);
		print_noscript();
	} else {
		render_page($type, $rec["{$type}_id"], false);
	}

	$last_seen = update_view_time($type, $rec["{$type}_id"]);

	if ($auth_user["javascript_enabled"]) {
		writeln('<script>');
		writeln();
		writeln('var hide_value = ' . $hide_value . ';');
		writeln('var expand_value = ' . $expand_value . ';');
		writeln('var auth_zid = "' . $auth_zid . '";');
		writeln('var last_seen = ' . $last_seen . ';');
		writeln();
		writeln('get_comments("' . $type . '", "' . $rec["{$type}_id"] . '");');
		writeln('render_page();');
		writeln();
		writeln('</script>');
	}
}


function user_page_link($zid, $link = false, $ac = true)
{
	global $protocol;

	$s = $protocol . "://" . str_replace("@", ".", $zid) . "/";
	if ($link) {
		$s = "<a href=\"$s\">$zid</a>";
	}
	if ($ac && $zid == "") {
		$s = "Anonymous Coward";
	}

	return $s;
}


function profile_picture($zid, $size)
{
	global $protocol;
	global $server_name;
	global $doc_root;

	list($user, $host) = explode("@", $zid);
	$path = "/pub/profile/$host/$user-$size.jpg";
	$time = fs_time("$doc_root/www$path");

	return "$protocol://$server_name$path?$time";
}


function make_clickable($text)
{
	global $protocol;
	global $server_name;

	$text = preg_replace("/\B(#)([A-Z0-9]+)/", "<a href=\"$protocol://$server_name/$2\">$0</a>", $text);
	$text = preg_replace("/\B(#)([a-z]{1,}[a-z0-9]*)/", "<a href=\"$protocol://$server_name/tag/$2\">$0</a>", $text);

	return $text;
}


function make_photo_links($text)
{
	global $protocol;
	global $server_name;

	$retina = true;
	$keywords = array("thumb", "small", "medium", "large", "frame", "left", "center", "right");
	preg_match_all("/\[\[Photo:[A-Za-z0-9| ]+\]\]/", $text, $matches);
	for ($i = 0; $i < count($matches[0]); $i++) {
                $a = explode("|", substr($matches[0][$i], 8, -2));
                //var_dump($a);
                $short_code = $a[0];
                $short_id = crypt_crockford_decode($short_code);
                if (db_has_rec("photo", $short_id)) {
			$photo = db_get_rec("photo", $short_id);
			$info = photo_info($photo);
			$frame = false;
			$label = "";

			if (in_array("thumb", $a)) {
				$frame = true;
				if ($retina) {
					$tag = "<img class=\"{$info["thumb_small_class"]}\" src=\"{$info["thumb_large_link"]}\"/>";
				} else {
					$tag = "<img class=\"{$info["thumb_small_class"]}\" src=\"{$info["thumb_small_link"]}\"/>";
				}
			} else if ($photo["has_medium"] && in_array("medium", $a)) {
				if ($retina) {
					$tag = "<img class=\"{$info["small_class"]}\" src=\"{$info["medium_link"]}\"/>";
				} else {
					$tag = "<img class=\"{$info["medium_class"]}\" src=\"{$info["medium_link"]}\"/>";
				}
			} else if ($photo["has_large"] && in_array("large", $a)) {
				if ($retina) {
					$tag = "<img class=\"{$info["big_class"]}\" src=\"{$info["large_link"]}\"/>";
				} else {
					$tag = "<img class=\"{$info["large_class"]}\" src=\"{$info["large_link"]}\"/>";
				}
			} else {
				if ($retina) {
					$tag = "<img class=\"{$info["tiny_class"]}\" src=\"{$info["small_link"]}\"/>";
				} else {
					$tag = "<img class=\"{$info["small_class"]}\" src=\"{$info["small_link"]}\"/>";
				}
			}
			$tag = "<a href=\"$protocol://$server_name/photo/$short_code\">$tag</a>";
			for ($j = 1; $j < count($a); $j++) {
				if (!in_array($a[$j], $keywords)) {
					$label = "<div>{$a[$j]}</div>";
				}
			}
			if ($frame || $label !== "") {
				$tag = "<div class=\"photo_frame\">$tag$label</div>";
			}

			$text = str_replace($matches[0][$i], $tag, $text);
		}
	}
	//var_dump($matches);

	return $text;
}


function public_path($time)
{
	return "/pub/" . gmdate("Y", $time) . "/" . gmdate("m", $time) . "/" . gmdate("d", $time);
}


function format_money($cents)
{
	return number_format((int) $cents / 100, 2);
}


$request_uri = $_SERVER["REQUEST_URI"];
if (string_has($request_uri, "?")) {
	$request_script = substr($request_uri, 0, strpos($request_uri, "?"));
} else {
	$request_script = $request_uri;
}

if (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == "on" || $_SERVER["HTTPS"] == 1) || isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https") {
	$protocol = "https";
} else {
	$protocol = "http";
}
if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
	$remote_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
} else {
	$remote_ip = $_SERVER["REMOTE_ADDR"];
}

$server_conf = db_get_conf("server_conf");
$http_host = $_SERVER["HTTP_HOST"];
$user_page = "";
$a = explode(".", $http_host);
if (count($a) == 2) {
	if ($http_host != $server_name) {
		header("Location: $protocol://$server_name$request_uri");
		die();
	}
} else if (count($a) == 3) {
	if ($a[1] . "." . $a[2] != $server_name) {
		header("Location: $protocol://" . $a[0] . ".$server_name$request_uri");
		die();
	}
	if ($a[0] == "www") {
		header("Location: $protocol://$server_name$request_uri");
		die();
	}
	$user_page = strtolower($a[0]);
	if (!string_uses($user_page, "[a-z][0-9]")) {
		die("invalid user page [$user_page]");
	}
	if (!is_local_user("$user_page@$server_name")) {
		die("user not found [$user_page]");
	}
}
if ($user_page != "") {
	$zid = "$user_page@$server_name";
	$user_conf = db_get_conf("user_conf", $zid);
}

check_auth();

if ($auth_zid != "") {
	date_default_timezone_set($auth_user["time_zone"]);
}
