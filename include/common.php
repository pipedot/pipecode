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

$top_root = substr(dirname(__FILE__), 0, -8);
$doc_root = "$top_root/www";
$main_root = "$top_root/main";
$user_root = "$top_root/user";

set_include_path("$top_root/include");

include("$top_root/lib/tools/tools.php");
if (fs_is_file("$top_root/conf.php")) {
	include("$top_root/conf.php");
} else {
	include("$top_root/setup.php");
	die();
}

$db_table["captcha"]["key"] = "captcha_id";
$db_table["captcha"]["col"][] = "captcha_id";
$db_table["captcha"]["col"][] = "question";
$db_table["captcha"]["col"][] = "answer";

$db_table["captcha_challenge"]["key"] = "remote_ip";
$db_table["captcha_challenge"]["col"][] = "remote_ip";
$db_table["captcha_challenge"]["col"][] = "captcha_id";

$db_table["comment"]["key"] = "cid";
$db_table["comment"]["col"][] = "cid";
$db_table["comment"]["col"][] = "sid";
$db_table["comment"]["col"][] = "pid";
$db_table["comment"]["col"][] = "qid";
$db_table["comment"]["col"][] = "parent";
$db_table["comment"]["col"][] = "zid";
$db_table["comment"]["col"][] = "time";
//$db_table["comment"]["col"][] = "score";
//$db_table["comment"]["col"][] = "rid";
$db_table["comment"]["col"][] = "subject";
$db_table["comment"]["col"][] = "comment";

$db_table["comment_vote"]["key"][] = "cid";
$db_table["comment_vote"]["key"][] = "zid";
$db_table["comment_vote"]["col"][] = "cid";
$db_table["comment_vote"]["col"][] = "zid";
$db_table["comment_vote"]["col"][] = "rid";
$db_table["comment_vote"]["col"][] = "time";

$db_table["default_conf"]["key"][] = "conf";
$db_table["default_conf"]["key"][] = "name";
$db_table["default_conf"]["col"][] = "conf";
$db_table["default_conf"]["col"][] = "name";
$db_table["default_conf"]["col"][] = "value";

$db_table["email_challenge"]["key"] = "challenge";
$db_table["email_challenge"]["col"][] = "challenge";
$db_table["email_challenge"]["col"][] = "username";
$db_table["email_challenge"]["col"][] = "email";
$db_table["email_challenge"]["col"][] = "expires";

$db_table["feed"]["key"] = "fid";
$db_table["feed"]["col"][] = "fid";
$db_table["feed"]["col"][] = "time";
$db_table["feed"]["col"][] = "uri";
$db_table["feed"]["col"][] = "title";
$db_table["feed"]["col"][] = "link";
//$db_table["feed"]["col"][] = "data";

$db_table["feed_item"]["key"][] = "fid";
$db_table["feed_item"]["key"][] = "time";
$db_table["feed_item"]["col"][] = "fid";
$db_table["feed_item"]["col"][] = "time";
$db_table["feed_item"]["col"][] = "title";
$db_table["feed_item"]["col"][] = "link";

$db_table["feed_user"]["key"][] = "zid";
$db_table["feed_user"]["key"][] = "fid";
$db_table["feed_user"]["col"][] = "zid";
$db_table["feed_user"]["col"][] = "fid";
$db_table["feed_user"]["col"][] = "col";
$db_table["feed_user"]["col"][] = "pos";

$db_table["mail"]["key"] = "mail_id";
$db_table["mail"]["col"][] = "mail_id";
$db_table["mail"]["col"][] = "body";
$db_table["mail"]["col"][] = "in_reply_to";
$db_table["mail"]["col"][] = "location";
$db_table["mail"]["col"][] = "mail_from";
$db_table["mail"]["col"][] = "message_id";
$db_table["mail"]["col"][] = "rcpt_to";
$db_table["mail"]["col"][] = "received_time";
$db_table["mail"]["col"][] = "reply_to";
$db_table["mail"]["col"][] = "size";
$db_table["mail"]["col"][] = "subject";
$db_table["mail"]["col"][] = "zid";

$db_table["page"]["key"] = "slug";
$db_table["page"]["col"][] = "slug";
$db_table["page"]["col"][] = "title";
$db_table["page"]["col"][] = "body";

$db_table["pipe"]["key"] = "pid";
$db_table["pipe"]["col"][] = "pid";
$db_table["pipe"]["col"][] = "tid";
$db_table["pipe"]["col"][] = "zid";
$db_table["pipe"]["col"][] = "editor";
$db_table["pipe"]["col"][] = "title";
$db_table["pipe"]["col"][] = "ctitle";
$db_table["pipe"]["col"][] = "icon";
$db_table["pipe"]["col"][] = "time";
$db_table["pipe"]["col"][] = "closed";
$db_table["pipe"]["col"][] = "reason";
$db_table["pipe"]["col"][] = "story";

$db_table["pipe_history"]["key"][] = "pid";
$db_table["pipe_history"]["key"][] = "zid";
$db_table["pipe_history"]["col"][] = "pid";
$db_table["pipe_history"]["col"][] = "zid";
$db_table["pipe_history"]["col"][] = "time";

$db_table["pipe_vote"]["key"][] = "pid";
$db_table["pipe_vote"]["key"][] = "zid";
$db_table["pipe_vote"]["col"][] = "pid";
$db_table["pipe_vote"]["col"][] = "zid";
$db_table["pipe_vote"]["col"][] = "time";
$db_table["pipe_vote"]["col"][] = "value";

$db_table["poll_answer"]["key"] = "aid";
$db_table["poll_answer"]["col"][] = "aid";
$db_table["poll_answer"]["col"][] = "qid";
$db_table["poll_answer"]["col"][] = "answer";
$db_table["poll_answer"]["col"][] = "position";
//$db_table["poll_answer"]["col"][] = "votes";

$db_table["poll_history"]["key"][] = "qid";
$db_table["poll_history"]["key"][] = "zid";
$db_table["poll_history"]["col"][] = "qid";
$db_table["poll_history"]["col"][] = "zid";
$db_table["poll_history"]["col"][] = "time";

$db_table["poll_question"]["key"] = "qid";
$db_table["poll_question"]["col"][] = "qid";
$db_table["poll_question"]["col"][] = "type_id";
$db_table["poll_question"]["col"][] = "zid";
$db_table["poll_question"]["col"][] = "time";
$db_table["poll_question"]["col"][] = "question";
//$db_table["poll_question"]["col"][] = "votes";

$db_table["poll_vote"]["key"][] = "qid";
$db_table["poll_vote"]["key"][] = "zid";
$db_table["poll_vote"]["col"][] = "qid";
$db_table["poll_vote"]["col"][] = "zid";
$db_table["poll_vote"]["col"][] = "time";
$db_table["poll_vote"]["col"][] = "score";

$db_table["story"]["key"] = "sid";
$db_table["story"]["col"][] = "sid";
$db_table["story"]["col"][] = "pid";
$db_table["story"]["col"][] = "tid";
$db_table["story"]["col"][] = "title";
$db_table["story"]["col"][] = "ctitle";
$db_table["story"]["col"][] = "icon";
$db_table["story"]["col"][] = "time";
$db_table["story"]["col"][] = "story";

$db_table["story_history"]["key"][] = "sid";
$db_table["story_history"]["key"][] = "zid";
$db_table["story_history"]["col"][] = "sid";
$db_table["story_history"]["col"][] = "zid";
$db_table["story_history"]["col"][] = "time";

$db_table["topic"]["key"] = "tid";
$db_table["topic"]["col"][] = "tid";
$db_table["topic"]["col"][] = "topic";
$db_table["topic"]["col"][] = "icon";
$db_table["topic"]["col"][] = "promoted";

$db_table["user_conf"]["key"] = "zid";
$db_table["user_conf"]["col"][] = "zid";
$db_table["user_conf"]["col"][] = "name";
$db_table["user_conf"]["col"][] = "value";


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
	global $story_image_enabled;

	header_expires();
	header("Content-Type: text/html; charset=utf-8");

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
	writeln('<link rel="stylesheet" href="/style.css" type="text/css"/>');
	if ($request_script == "/") {
		writeln('<link rel="alternate" href="/atom" type="application/atom+xml" title="Stories">');
	}
	if ($auth_user["javascript_enabled"]) {
		writeln('<script type="text/javascript" src="/lib/jquery/jquery.js"></script>');
		writeln('<script type="text/javascript" src="/common.js"></script>');
	}

	writeln('</head>');
	writeln('<body>');

	writeln('<header>');
	writeln('<table class="title">');
	writeln('	<tr>');
	if ($user_page == "") {
		writeln('		<td><a href="/"><img alt="' . $server_title . '" src="/images/logo-top.png"/></a></td>');
	} else {
		writeln('		<td><a href="' . $protocol . '://' . $server_name . '/"><img alt="' . $server_title . '" src="/images/logo-top.png"/></a></td>');
	}

	if ($user_page == "") {
		if ($auth_zid == "") {
			$link_name[] = "Submit";
			$link_name[] = "Sign Up";
			$link_name[] = "Sign In";
		} else {
			$link_name[] = "Submit";
			$link_name[] = "Home";
			if (($auth_user["editor"] || $auth_user["admin"]) && $request_script != "/menu/") {
				$link_name[] = "Tools";
			}
			$link_name[] = "Sign Out";
		}
	} else {
		if ($auth_zid == "") {
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
			$icon = "contact-new";
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

		writeln("			<a href=\"$link\" class=\"icon_16\" style=\"background-image: url('/images/$icon-16.png')\">$name</a>" . ($i == count($link_name) - 1 ? '' : ' | '));
	}
	writeln("		</td>");

	writeln('	</tr>');
	writeln('</table>');
	writeln('</header>');
	if ($story_image_enabled && $user_page == "") {
		writeln('<img alt="Story" class="story_image" src="/images/logo-256.png"/>');
	}
}


function print_left_bar($type = "main", $selected = "stories")
{
	global $auth_zid;
	global $auth_user;
	global $server_name;
	global $user_page;

	if ($type == "main") {
		if ($auth_zid == "") {
			$section_name = array("stories", "pipe", "poll", "search", "topics", "feed");
			$section_link = array("", "pipe/", "poll/", "search", "topic", "feed/");
		} else {
			$section_name = array("stories", "pipe", "poll", "search", "topics");
			$section_link = array("", "pipe/", "poll/", "search", "topic");
		}
	} else if ($type == "user") {
		//$section_name = array("blog", "feed", "submissions", "comments", "achievements");
		//$section_link = array("blog", "feed", "submissions", "comments", "achievements");
		$section_name = array("comments");
		$section_link = array("comments");
	} elseif ($type == "account") {
		//$section_name = array("mail", "feed", "comments", "karma", "settings");
		//$section_link = array("mail/", "feed/edit", "comments", "karma/", "settings");
		$section_name = array("comments", "feed", "karma", "settings");
		$section_link = array("comments", "feed/edit", "karma/", "settings");
	}

	writeln('<nav>');
	for ($i = 0; $i < count($section_name); $i++) {
		$link = "/" . $section_link[$i];
		//if ($user_page == "" && $section_name[$i] == "feed" && $auth_zid != "") {
		//	$row = run_sql("select count(*) as feed_count from feed_user where zid = ?", array($auth_zid));
		//	if ($row[0]["feed_count"] > 0) {
		//		$link = "http://" . $auth_user["username"] . ".$server_name/";
		//	}
		//}
		if ($selected == $section_name[$i]) {
			writeln('	<a href="' . $link . '"><div class="section_active">' . $section_name[$i] . '</div></a>');
		} else {
			writeln('	<a href="' . $link . '"><div>' . $section_name[$i] . '</div></a>');
		}
	}

	if ($type == "main") {
		writeln('	<hr/>');
		$list = db_get_list("topic", "topic", array("promoted" => 1));
		$keys = array_keys($list);
		for ($i = 0; $i < count($keys); $i++) {
			$topic = $list[$keys[$i]]["topic"];
			if ($topic == $selected) {
				writeln('	<a href="/topic/' . $topic . '"><div class="section_active">' . $topic . '</div></a>');
			} else {
				writeln('	<a href="/topic/' . $topic . '"><div>' . $topic . '</div></a>');
			}
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

	$row = run_sql("select count(*) as mail_count from mail where zid = ? and location = 'Inbox'", array($auth_zid));
	$mail_count = (int) $row[0]["mail_count"];
	if ($mail_count > 0) {
		$mail = "Mail ($mail_count)";
	} else {
		$mail = "Mail";
	}
	$link = user_page_link($auth_zid);

	writeln('<div class="dialog_title">' . $auth_zid . '</div>');
	writeln('<div class="dialog_body">');
	writeln('<table class="fill">');
	writeln('	<tr>');
	writeln('		<td><a href="' . $link . 'comments"><div class="user_box_icon" style="background-image: url(/images/chat-32.png)">Comments</div></a></td>');
	writeln('		<td><a href="' . $link . '"><div class="user_box_icon" style="background-image: url(/images/news-32.png)">Feed</div></a></td>');
	writeln('	</tr>');
//	writeln('	<tr>');
//	writeln('		<td><a href="' . $link . 'karma/"><div class="user_box_icon" style="background-image: url(/images/karma-good-32.png)">Karma</div></a></td>');
//	writeln('		<td><a href="' . $link . '"><div class="user_box_icon" style="background-image: url(/images/news-32.png)">Feed</div></a></td>');
//	writeln('	</tr>');
//	writeln('	<tr>');
//	writeln('		<td><a href="' . $link . '"><div class="user_box_icon" style="background-image: url(/images/notepad-32.png)">Blog</div></a></td>');
//	writeln('		<td><a href="' . $link . 'comments"><div class="user_box_icon" style="background-image: url(/images/chat-32.png)">Comments</div></a></td>');
//	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td><a href="' . $link . 'mail/"><div class="user_box_icon" style="background-image: url(/images/mail-32.png)">' . $mail . '</div></a></td>');
	writeln('		<td><a href="' . $link . 'settings"><div class="user_box_icon" style="background-image: url(/images/tools-32.png)">Settings</div></a></td>');
	writeln('	</tr>');
//	writeln('	<tr>');
//	writeln('		<td><a href="http://' . $auth_user["username"] . '.' . $server_name . '/friends/"><div class="user_box_icon" style="background-image: url(/images/users-32.png)">Friends</div></a></td>');
//	writeln('		<td><div class="user_box_icon"></div></td>');
//	writeln('	</tr>');
	writeln('</table>');
	writeln('</div>');
}


function print_footer()
{
	global $user_page;
	global $server_name;
	global $server_title;
	global $server_slogan;

	if ($user_page == "") {
		writeln('<hr/>');
		writeln('<footer>');
		writeln('<table class="footer">');
		writeln('	<tr>');
		writeln('		<td>');
		writeln('			<a href="/about">About</a>');
		writeln('			<a href="http://bugs.' . $server_name . '/">Bugs</a>');
		//writeln('			<a href="/archive">Archive</a>');
		writeln('			<a href="/faq">FAQ</a>');
		writeln('			<a href="/atom" class="icon_16" style="background-image: url(\'/images/feed-16.png\')">Feed</a>');
		//writeln('			<a href="mailto:feedback@' . $server_name . '">Feedback</a>');
		writeln('			<a href="/privacy">Privacy</a>');
		writeln('			<a href="/terms">Terms</a>');
		writeln('		</td>');
		writeln('		<td>' . $server_title . ': ' . $server_slogan . '</td>');
		writeln('	</tr>');
		writeln('</table>');
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
	$clean = "";
	for ($i = 0; $i < strlen($dirty); $i++) {
		$c = substr($dirty, $i, 1);
		if (string_uses($c, "[a-z][A-Z][0-9] ")) {
			$clean .= $c;
		}
	}
	$clean = str_replace(" ", "-", strtolower(trim($clean)));
	return $clean;
}


function article_info($comment)
{
	global $server_name;

	$a = array();
	if ($comment["sid"] > 0) {
		$story = db_get_rec("story", $comment["sid"]);
		$a["type"] = "story";
		$a["title"] = $story["title"];
		$date = gmdate("Y-m-d", $story["time"]);
		$a["link"] = "http://$server_name/story/$date/" . $story["ctitle"];
	} else if ($comment["pid"] > 0) {
		$pipe = db_get_rec("pipe", $comment["pid"]);
		$a["type"] = "pipe";
		$a["title"] = $pipe["title"];
		$a["link"] = "http://$server_name/pipe/" . $comment["pid"];
	} else if ($comment["qid"] > 0) {
		$question = db_get_rec("poll_question", $comment["qid"]);
		$a["type"] = "poll";
		$a["title"] = $question["question"];
		$a["link"] = "http://$server_name/poll/" . $comment["qid"];
	}

	return $a;
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
	if ($auth_zid == "") {
		writeln('	<li>Sign in and uncheck the "Use JavaScript" option on your account settings page.</li>');
		writeln('	<li>Click <a href="">this link</a> to get a cookie that disables JavaScript. (not working yet)</li>');
	} else {
		writeln('	<li>Uncheck the "Use JavaScript" option on your <a href="' . user_page_link($auth_zid) . 'settings">account settings page</a>.</li>');
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


function is_local_user($zid)
{
	$row = run_sql("select value from user_conf where zid = ? and name = 'password'", array($zid));
	if (count($row) == 0) {
		return false;
	}

	return true;
}


function user_page_link($zid)
{
	global $protocol;

	return $protocol . "://" . str_replace("@", ".", $zid) . "/";
}


function str_replace_all($search, $replacement, $source)
{
	while (string_has($source, $search)) {
		$source = str_replace($search, $replacement, $source);
	}

	return $source;
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

