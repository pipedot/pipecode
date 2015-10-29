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

$doc_root = dirname(dirname(__FILE__));

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

include("sql.php");

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
$reasons["Spam"] = -1;

$story_image_style[1] = "None";
$story_image_style[2] = "Icon";
$story_image_style[3] = "Image";


function print_header($title = "", $link_name = [], $link_icon = [], $link_url = [], $spin_name = [], $spin_link = [])
{
	global $auth_user;
	global $auth_zid;
	global $doc_root;
	global $https_enabled;
	global $meta;
	global $notification_count;
	global $protocol;
	global $request_script;
	global $server_conf;
	global $server_name;
	global $server_title;
	global $user_page;
	global $zid;

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
		$picture = avatar_picture($zid, 128);
	}
	writeln('<title>' . $title . '</title>');
	writeln('<meta charset="utf-8">');
	writeln('<meta name="viewport" content="width=device-width, initial-scale=1">');
	print $meta;

	if ($user_page === "") {
		writeln('<link rel="icon" href="/favicon.ico" sizes="16x16 32x32 48x48" type="image/x-icon">');
	} else {
		writeln('<link rel="icon" href="' . avatar_picture($zid, 64) . '" sizes="64x64" type="image/png">');
	}

	writeln('<link rel="stylesheet" href="' . $protocol . '://' . $server_name . '/style.css?t=' . fs_time("$doc_root/www/style.css") . '">');
	if ($auth_user["large_text_enabled"]) {
		writeln('<style>');
		writeln('html { font-size: 80%; }');
		writeln('</style>');
	}
	if ($user_page === "" && $request_script == "/") {
		writeln('<link rel="alternate" href="/atom" type="application/atom+xml" title="Stories">');
	}
	if ($auth_user["javascript_enabled"]) {
		writeln('<script src="' . $protocol . '://' . $server_name . '/lib/jquery/jquery.js?t=' . fs_time("$doc_root/www/lib/jquery/jquery.js") . '"></script>');
		writeln('<script src="' . $protocol . '://' . $server_name . '/lib/jquery/jquery-ui.js?t=' . fs_time("$doc_root/www/lib/jquery/jquery-ui.js") . '"></script>');
		writeln('<script src="' . $protocol . '://' . $server_name . '/common.js?t=' . fs_time("$doc_root/www/common.js") . '"></script>');
	}

	writeln('</head>');
	writeln('<body>');

	writeln('<header class="title">');

	if ($user_page === "") {
		writeln('	<div><a class="logo" href="' . $protocol . '://' . $server_name . '/"></a></div>');
	} elseif (count($spin_name) == 0) {
		writeln('	<div class="spinner">');
		writeln('		<a class="pic" href="/" style="background-image: url(' . $picture . ')"><div class="top"></div></a>');
		writeln('		<a class="txt" href="/">' . $zid . '</a>');
		writeln('	</div>');
	} else {
		writeln('	<div class="spinner">');
		writeln('		<a class="pic" href="/" style="background-image: url(' . $picture . ')"><div class="beg"></div></a>');
		for ($i = 0; $i < count($spin_name); $i++) {
			writeln('		<a class="bar" href="' . $spin_link[$i] . '">' . $spin_name[$i] . '</a>');
			if ($i < count($spin_name) - 1) {
				writeln('		<a class="sep" href="' . $spin_link[$i] . '"></a>');
			}
		}
		writeln('		<a class="end" href="' . $spin_link[$i - 1] . '"></a>');
		writeln('	</div>');
	}

	if ($auth_zid === "") {
		$notification_count = 0;
	} else {
		$notification_count = db_get_count("notification", ["zid" => $auth_zid]);
	}

	if ($user_page === "") {
		if ($auth_zid === "") {
			if ($server_conf["submit_enabled"]) {
				$link_name[] = "Submit";
			}
			if ($server_conf["register_enabled"]) {
				$link_name[] = "Register";
			}
			$link_name[] = "Login";
		} else {
			if ($server_conf["submit_enabled"]) {
				$link_name[] = "Submit";
			}
			$link_name[] = "Menu";
			if (($auth_user["admin"] || $auth_user["editor"]) && $request_script != "/menu/") {
				$link_name[] = "Tools";
			}
			if ($notification_count > 0) {
				$link_name[] = "Notifications";
			}
			$link_name[] = "Logout";
		}
	} else {
		if ($auth_zid === "") {
			$link_name[] = "Server";
			$link_name[] = "Login";
		} else {
			//if ($request_script != "/menu/") {
			//	$link_name[] = "Menu";
			//}
			$link_name[] = "Server";
			if ($notification_count > 0) {
				$link_name[] = "Notifications";
			}
			$link_name[] = "Logout";
		}
	}

	writeln("	<ul>");
	for ($i = 0; $i < count($link_name); $i++) {
		$name = $link_name[$i];
		$icon = "";
		$link = "";

		if ($name == "Submit") {
			$icon = "notepad";
			$link = "/submit";
		} else if ($name == "Menu") {
			$icon = "user";
			$link = user_link($auth_zid);
		} else if ($name == "Notifications") {
			$name = "Notifications ($notification_count)";
			$icon = "bulb";
			$link = user_link($auth_zid) . "notification/";
		} else if ($name == "Server") {
			$name = $server_title;
			$icon = "logo";
			$link = "$protocol://$server_name/";
		} else if ($name == "Tools") {
			$icon = "tools";
			$link = "/tools/";
		} else if ($name == "Register") {
			$icon = "register";
			$link = ($https_enabled ? "https" : $protocol ) . "://$server_name/register";
		} else if ($name == "Login") {
			$icon = "users";
			$link = ($https_enabled ? "https" : $protocol ) . "://$server_name/login";
		} else if ($name == "Logout") {
			$icon = "exit";
			$link = "$protocol://$server_name/logout";
		}

		if ($icon == "") {
			$icon = $link_icon[$i];
		}
		if ($link == "") {
			$link = $link_url[$i];
		}

		writeln("		<li><a href=\"$link\" class=\"icon-16 {$icon}-16\">" . get_text($name) . "</a></li>");
	}
	writeln("	</ul>");
	writeln('</header>');
}


function print_main_nav($selected)
{
	global $auth_zid;
	global $auth_user;
	global $server_name;
	global $zid;

	if ($auth_zid === "") {
		$section_name = array("stories", "pipe", "poll", "search", "topics", "feed", "stream");
		$section_link = array("story/", "pipe/", "poll/", "search", "topic/", "feed/", "stream/");
	} else {
		$section_name = array("stories", "pipe", "poll", "search", "topics", "stream");
		$section_link = array("story/", "pipe/", "poll/", "search", "topic/", "stream/");
	}

	writeln('<nav class="sections">');

	for ($i = 0; $i < count($section_name); $i++) {
		$link = "/" . $section_link[$i];
		if ($selected == $section_name[$i]) {
			writeln('	<a class="active" href="' . $link . '">' . get_text($section_name[$i]) . '</a>');
		} else {
			writeln('	<a href="' . $link . '">' . get_text($section_name[$i]) . '</a>');
		}
	}

	writeln('	<div class="topics">');
	writeln('	<hr>');
	$list = db_get_list("topic", "topic", array("promoted" => 1));
	$keys = array_keys($list);
	for ($i = 0; $i < count($keys); $i++) {
		$topic = $list[$keys[$i]]["topic"];
		if ($topic == $selected) {
			writeln('	<a class="active" href="/topic/' . $topic . '">' . $topic . '</a>');
		} else {
			writeln('	<a href="/topic/' . $topic . '">' . $topic . '</a>');
		}
	}
	writeln('	</div>');

	writeln('</nav>');
}


function print_user_nav($selected)
{
	global $auth_zid;
	global $auth_user;
	global $server_name;
	global $zid;

//	if ($auth_zid === $zid) {
//		$section_name = array("overview", "karma", "comments", "journal", "topics", "feed", "reader", "stream");
//		$section_link = array("", "karma/", "comments", "journal/", "topic", "feed/", "reader/", "stream/");
//	} else {
//		$section_name = array("overview", "journal", "stream", "comments", "feed", "karma");
//		$section_link = array("", "journal/", "stream/", "comments", "feed/", "karma/");
//	}
	$section_name = array("journal");
	$section_link = array("journal/");

	writeln('<nav class="sections">');

	for ($i = 0; $i < count($section_name); $i++) {
		$link = "/" . $section_link[$i];
		if ($selected == $section_name[$i]) {
			writeln('	<a class="active" href="' . $link . '">' . $section_name[$i] . '</a>');
		} else {
			writeln('	<a href="' . $link . '">' . $section_name[$i] . '</a>');
		}
	}

	$row = sql("select distinct topic from journal where zid = ? order by topic", $zid);
	if (count($row) > 0) {
		writeln('	<div class="topics">');
		writeln('	<hr>');
		for ($i = 0; $i < count($row); $i++) {
			$topic = $row[$i]["topic"];
			if ($topic == $selected) {
				writeln('	<a class="active" href="/topic/' . $topic . '">' . $topic . '</a>');
			} else {
				writeln('	<a href="/topic/' . $topic . '">' . $topic . '</a>');
			}
		}
		writeln('	</div>');
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
	$link = user_link($auth_zid);

	writeln('<div class="dialog-title">' . $auth_zid . '</div>');
	writeln('<div class="dialog-body">');
	writeln('<table class="side-link-two">');
	writeln('	<tr>');
	//writeln('		<td><a href="' . $link . 'comments"><div class="chat-32">Comments</div></a></td>');
	writeln('		<td><a class="news-32" href="' . $link . 'feed/">Feed</a></td>');
	writeln('		<td><a class="notepad-32" href="' . $link . 'journal/">Journal</a></td>');
	writeln('	</tr>');
//	writeln('	<tr>');
//	writeln('		<td><a class="news-32" href="' . $link . 'karma/"><div class="user-box-icon" style="background-image: url(/images/karma-good-32.png)">Karma</div></a></td>');
//	writeln('		<td><a class="news-32" href="' . $link . '"><div class="user-box-icon" style="background-image: url(/images/news-32.png)">Feed</div></a></td>');
//	writeln('	</tr>');
//	writeln('	<tr>');
//	writeln('		<td><a class="news-32" href="' . $link . 'comments"><div class="user-box-icon" style="background-image: url(/images/chat-32.png)">Comments</div></a></td>');
//	writeln('		<td><a class="news-32" href="http://' . $auth_user["username"] . '.' . $server_name . '/friends/"><div class="user-box-icon" style="background-image: url(/images/users-32.png)">Friends</div></a></td>');
//	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td><a class="mail-32" href="' . $link . 'mail/">' . $mail . '</a></td>');
	writeln('		<td><a class="tools-32" href="' . $link . 'settings">Settings</a></td>');
	writeln('	</tr>');
//	writeln('	<tr>');
//	writeln('		<td><a class="reader-32" href="' . $link . 'reader/">Reader</a></td>');
//	writeln('		<td><a class="internet-32" href="' . $link . 'stream/">Stream</a></td>');
//	writeln('	</tr>');
	writeln('</table>');
	writeln('</div>');
}


function score_icon($score)
{
	$a = explode(", ", $score);
	$number = $a[0];
	if (count($a) == 1) {
		$reason = "";
	} else {
		$reason = $a[1];
	}

	if ($number >= 5) {
		return "face-grin";
	}
	switch ($reason) {
		case "Offtopic":
			$icon = "face-plain";
			break;
		case "Flamebait":
			$icon = "face-crying";
			break;
		case "Troll":
			$icon = "face-crying";
			break;
		case "Redundant":
			$icon = "face-sad";
			break;
		case "Insightful":
			$icon = "face-smile";
			break;
		case "Interesting":
			$icon = "face-smile";
			break;
		case "Informative":
			$icon = "face-smile";
			break;
		case "Funny":
			$icon = "face-grin";
			break;
		case "Spam":
			$icon = "junk";
			break;
		default:
			if ($number >= 5) {
				$icon = "face-grin";
			} else if ($number >= 2) {
				$icon = "face-smile";
			} else if ($number >= 1) {
				$icon = "face-plain";
			} else if ($number >= 0) {
				$icon = "face-sad";
			} else {
				$icon = "face-crying";
			}
	}

	return $icon;
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
		$s .= "<a class=\"pages-left\" href=\"?page=" . ($page - 1) . "\" title=\"Back\"></a>";
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
		$s .= "<a class=\"pages-right\" href=\"?page=" . ($page + 1) . "\" title=\"Next\"></a>";
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
		writeln('	<a href="/atom" class="icon-16 feed-16">Feed</a>');
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
	global $http_host;

	$auth = @$_COOKIE["auth"];
	$map = map_from_url_string($auth);
	$zid = @$map["zid"];
	$key = @$map["key"];
	if ($zid != "" && $key != "") {
		db_del_rec("login", ["zid" => $zid, "login_key" => $key]);
	}

	setcookie("auth", "", time() - (5 * DAYS), "/", ".$server_name");

	// XXX: attempt to kill cookies on servers with a misconfigured $server_name
	if ($server_name != $http_host) {
		setcookie("auth", "", time() - (5 * DAYS), "/");
		setcookie("auth", "", time() - (5 * DAYS), "/", ".$http_host");
	}
}


function check_auth()
{
	global $auth_key;
	global $auth_zid;
	global $auth_user;
	global $now;

	$auth_zid = "";

	$auth = @$_COOKIE["auth"];
	$map = map_from_url_string($auth);
	$zid = @$map["zid"];
	$key = @$map["key"];

	if ($zid == "") {
		$auth_user = db_get_conf("user_conf", "");
		$auth_user["javascript_enabled"] = 0;
		return;
	}
	if (!string_uses($key, "[0-9]abcdef") || strlen($key) != 64) {
		expire_auth();
		$auth_user = db_get_conf("user_conf", "");
		$auth_user["javascript_enabled"] = 0;
		return;
	}
	if (!string_uses($zid, "[a-z][0-9]@.-")) {
		expire_auth();
		fatal("Invalid zid");
	}

	$login = db_find_rec("login", ["zid" => $zid, "login_key" => $key]);
	if ($login === false) {
		expire_auth();
		fatal("Login key not found");
	}
	$login["last_time"] = $now;
	db_set_rec("login", $login);

	$auth_user = db_get_conf("user_conf", $zid);
	$auth_zid = $zid;
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


function article_info($comment)
{
	global $server_name;
	global $protocol;

	$short = db_get_rec("short", $comment["article_id"]);
	$type_id = $short["type_id"];
	$a = array();
	$a["type_id"] = $type_id;
	$a["type"] = item_type($type_id);
	if ($type_id == TYPE_STORY) {
		$story = db_get_rec("story", $comment["article_id"]);
		$a["title"] = $story["title"];
		$date = gmdate("Y-m-d", $story["publish_time"]);
		$a["link"] = "$protocol://$server_name/story/$date/" . $story["slug"];
	} else if ($type_id == TYPE_ARTICLE) {
		$article = db_get_rec("article", $comment["article_id"]);
		$a["title"] = $article["title"];
		$a["link"] = "$protocol://$server_name/article/" . crypt_crockford_encode($article["article_id"]);
	} else if ($type_id == TYPE_PIPE) {
		$pipe = db_get_rec("pipe", $comment["article_id"]);
		$a["title"] = $pipe["title"];
		$a["link"] = "$protocol://$server_name/pipe/" . crypt_crockford_encode($pipe["pipe_id"]);
	} else if ($type_id == TYPE_POLL) {
		$poll = db_get_rec("poll", $comment["article_id"]);
		$a["title"] = $poll["question"];
		$a["link"] = "$protocol://$server_name/poll/" . gmdate("Y-m-d", $poll["publish_time"]) . "/" . $poll["slug"];
	} else if ($type_id == TYPE_JOURNAL) {
		$journal = db_get_rec("journal", $comment["article_id"]);
		$a["title"] = $journal["title"];
		if ($journal["published"]) {
			$a["link"] = user_link($journal["zid"]) . "journal/" . gmdate("Y-m-d", $journal["publish_time"]) . "/" . $journal["slug"];
		} else {
			$a["link"] = user_link($journal["zid"]) . "journal/" . crypt_crockford_encode($journal["journal_id"]);
		}
	} else if ($type_id == TYPE_CARD) {
		$card = db_get_rec("card", array("card_id" => $comment["article_id"]));
		$a["title"] = "#" . crypt_crockford_encode($card["card_id"]);
		$a["link"] = "$protocol://$server_name/card/" . crypt_crockford_encode($card["card_id"]);
	}

	return $a;
}


function check_article_type($type)
{
	$a = array("card", "story", "pipe", "poll", "journal");
	if (!in_array($type, $a)) {
		fatal("Unknown article type");
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
	} else {
		writeln('	<li>Uncheck the "Use JavaScript" option on your <a href="' . user_link($auth_zid) . 'profile/">account settings page</a>.</li>');
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


function create_short($type_id)
{
	$short = db_new_rec("short");
	$short["type_id"] = $type_id;
	db_set_rec("short", $short);

	return db_last();
}


function is_local_user($zid)
{
	$row = sql("select value from user_conf where zid = ? and name = 'password'", $zid);
	if (count($row) == 0) {
		return false;
	}

	return true;
}


function update_view_time($article_id)
{
	global $auth_zid;

	if ($auth_zid === "") {
		$last_seen = 0;
	} else {
		$comment_view = db_find_rec("comment_view", ["article_id" => $article_id, "zid" => $auth_zid]);
		if ($comment_view) {
			$comment_view["last_time"] = $comment_view["time"];
			$last_seen = $comment_view["time"];
		} else {
			$comment_view = db_new_rec("comment_view");
			$comment_view["article_id"] = $article_id;
			$comment_view["zid"] = $auth_zid;
			$comment_view["last_time"] = 0;
			$last_seen = 0;
		}
		$comment_view["comments_clean"] = 0;
		$comment_view["comments_total"] = 0;
		$comment_view["time"] = time();
		db_set_rec("comment_view", $comment_view);
	}

	return $last_seen;
}


function revert_view_time($article_id)
{
	global $auth_zid;

	if ($auth_zid === "") {
		return;
	}

	$comment_view = db_find_rec("comment_view", ["article_id" => $article_id, "zid" => $auth_zid]);
	if ($comment_view) {
		$comment_view["time"] = $comment_view["last_time"];
		db_set_rec("comment_view", $comment_view);
	}
}


function count_comments($article_id, $article_type_id)
{
	global $auth_zid;
	global $auth_user;

	if ($article_id == 0) {
		$comments["count"] = 0;
		$comments["new"] = 0;
		$comments["tag"] = nget_text("<b>$1</b> comment", "<b>$1</b> comments");
		return;
	}
	$article_type = item_type($article_type_id);
	$article = db_get_rec($article_type, $article_id);

	$comments = [];
	if ($auth_user["show_junk_enabled"]) {
		$comments["count"] = $article["comments_total"];
	} else {
		$comments["count"] = $article["comments_clean"];
	}
//	if ($comments["count"] == 1) {
//		$comments["label"] = " comment";
//	} else {
//		$comments["label"] = " comments";
//	}

	if ($auth_zid === "") {
		$new = 0;
	} else {
		$comment_view = db_find_rec("comment_view", ["article_id" => $article_id, "zid" => $auth_zid]);
		if ($comment_view) {
			if ($auth_user["show_junk_enabled"]) {
				$new = $comment_view["comments_total"];
			} else {
				$new = $comment_view["comments_clean"];
			}
		} else {
			$comment_view = db_new_rec("comment_view");
			$comment_view["article_id"] = $article_id;
			$comment_view["zid"] = $auth_zid;
			$new = -1;
		}
		if ($new == -1) {
			if ($auth_user["show_junk_enabled"]) {
				$row = sql("select count(*) as comments from comment where article_id = ? and edit_time > ?", $article_id, $comment_view["time"]);
				$new = $row[0]["comments"];
				$comment_view["comments_total"] = $new;
			} else {
				$row = sql("select count(*) as comments from comment where article_id = ? and edit_time > ? and clean = 1", $article_id, $comment_view["time"]);
				$new = $row[0]["comments"];
				$comment_view["comments_clean"] = $new;
			}
			db_set_rec("comment_view", $comment_view);
		}
	}
	$comments["new"] = $new;
//	$comments["tag"] = "<b>" . $comments["count"] . "</b> " . $comments["label"];
	$comments["tag"] = nget_text("<b>$1</b> comment", "<b>$1</b> comments", $comments["count"], [$comments["count"]]);
	if ($comments["new"] > 0) {
		$comments["tag"] .= ", " . nget_text("<b>$1</b> new", "<b>$1</b> new", $comments["new"], [$comments["new"]]);
	}

	return $comments;
}


function recount_comments_recurse($row, $head_id)
{
	$s = "";
	for ($i = 0; $i < count($row); $i++) {
		if ($row[$i]["junk_status"] <= 0 && $row[$i]["parent_id"] == $head_id) {
			$s .= $row[$i]["comment_id"] . "," . recount_comments_recurse($row, $row[$i]["comment_id"]);
		}
	}

	return $s;
}


function recount_comments($article_id)
{
	$short = db_get_rec("short", $article_id);
	$article_type_id = $short["type_id"];

	$total = db_get_count("comment", ["article_id" => $article_id]);
	$clean = 0;
	$new_clean = [];
	$old_clean = [];
	$s = "";

	$row = sql("select comment_id, clean, junk_status, parent_id from comment where article_id = ? order by publish_time", $article_id);
	for ($i = 0; $i < count($row); $i++) {
		$comment_id = $row[$i]["comment_id"];
		if ($row[$i]["junk_status"] <= 0 && $row[$i]["parent_id"] == 0) {
			$s .= "$comment_id," . recount_comments_recurse($row, $comment_id);
		}
		$old_clean[$comment_id] = $row[$i]["clean"];
	}
	if (substr($s, -1) == ",") {
		$s = substr($s, 0, -1);
	}
	if ($s === "") {
		$a = [];
	} else {
		$a = explode(",", $s);
	}
	for ($i = 0; $i < count($a); $i++) {
		$new_clean[$a[$i]] = 1;
	}
	$clean = count($a);
	//writeln("total [$total] clean [$clean] s [$s]");
	$keys = array_keys($old_clean);
	for ($i = 0; $i < count($keys); $i++) {
		$comment_id = $keys[$i];
		$old_value = (int) $old_clean[$comment_id];
		$new_value = (int) array_key_exists($comment_id, $new_clean);
		//writeln("checking comment_id [$comment_id] old [$old_value] new [$new_value]");
		if ($old_value != $new_value) {
			sql("update comment set clean = ? where comment_id = ?", $new_value, $comment_id);
			//writeln("updating [$comment_id]");
		}
	}

	$article_type = item_type($article_type_id);
	$article = db_get_rec($article_type, $article_id);
	if ($article["comments_clean"] != $clean || $article["comments_total"] != $total) {
		$article["comments_clean"] = $clean;
		$article["comments_total"] = $total;
		db_set_rec($article_type, $article);
	}

	sql("update comment_view set comments_clean = -1, comments_total = -1 where article_id = ?", $article_id);
}


function print_comments($type_id, $rec)
{
	global $auth_zid;
	global $auth_user;
	global $can_moderate;
	global $hide_value;
	global $expand_value;
	global $doc_root;

	if ($auth_zid !== "") {
		$can_moderate = true;
		$hide_value = $auth_user["hide_threshold"];
		$expand_value = $auth_user["expand_threshold"];
		$inline_reply = $auth_user["inline_reply_enabled"];
		$wysiwyg_enabled = $auth_user["wysiwyg_enabled"];
	} else {
		$can_moderate = false;
		$hide_value = -1;
		$expand_value = 0;
		$inline_reply = false;
		$wysiwyg_enabled = false;
	}

	$type = item_type($type_id);
	if ($auth_user["javascript_enabled"]) {
		print_sliders($rec["{$type}_id"], $type_id);
		print_noscript();
	} else {
		render_page($type_id, $rec["{$type}_id"], false);
	}

	$last_seen = update_view_time($rec["{$type}_id"]);

	if ($auth_user["javascript_enabled"]) {
		if ($wysiwyg_enabled && $inline_reply) {
			writeln('<script src="/lib/ckeditor/ckeditor.js"></script>');
		}
		writeln('<script>');
		writeln();
		writeln('var hide_value = ' . $hide_value . ';');
		writeln('var expand_value = ' . $expand_value . ';');
		writeln('var auth_zid = "' . $auth_zid . '";');
		writeln('var last_seen = ' . $last_seen . ';');
		writeln('var inline_reply = ' . ($inline_reply ? "true" : "false") . ';');
		writeln('var wysiwyg_enabled = ' . ($wysiwyg_enabled ? "true" : "false") . ';');
		writeln();
		writeln('get_comments("' . $type . '", "' . crypt_crockford_encode($rec["{$type}_id"]) . '");');
		writeln('render_page();');
		writeln();
		if ($wysiwyg_enabled && $inline_reply) {
			writeln('CKEDITOR.timestamp = "' . fs_time("$doc_root/www/lib/ckeditor/config.js") . '";');
			writeln();
		}
		writeln('</script>');
	}
}


function avatar_picture($zid, $size)
{
	global $protocol;
	global $server_name;

	$row = sql("select value from user_conf where name = 'avatar_id' and zid = ?", $zid);
	if (count($row) == 0) {
		return "";
	}
	$avatar_code = crypt_crockford_encode($row[0]["value"]);
	if ($size == 64) {
		$ext = "png";
	} else {
		$ext = "jpg";
	}

	return "$protocol://$server_name/avatar/$avatar_code-$size.$ext";
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
					$tag = "<img class=\"{$info["thumb_small_class"]}\" src=\"{$info["thumb_large_link"]}\">";
				} else {
					$tag = "<img class=\"{$info["thumb_small_class"]}\" src=\"{$info["thumb_small_link"]}\">";
				}
			} else if ($photo["has_medium"] && in_array("medium", $a)) {
				if ($retina) {
					$tag = "<img class=\"{$info["small_class"]}\" src=\"{$info["medium_link"]}\">";
				} else {
					$tag = "<img class=\"{$info["medium_class"]}\" src=\"{$info["medium_link"]}\">";
				}
			} else if ($photo["has_large"] && in_array("large", $a)) {
				if ($retina) {
					$tag = "<img class=\"{$info["big_class"]}\" src=\"{$info["large_link"]}\">";
				} else {
					$tag = "<img class=\"{$info["large_class"]}\" src=\"{$info["large_link"]}\">";
				}
			} else {
				if ($retina) {
					$tag = "<img class=\"{$info["tiny_class"]}\" src=\"{$info["small_link"]}\">";
				} else {
					$tag = "<img class=\"{$info["small_class"]}\" src=\"{$info["small_link"]}\">";
				}
			}
			$tag = "<a href=\"$protocol://$server_name/photo/$short_code\">$tag</a>";
			for ($j = 1; $j < count($a); $j++) {
				if (!in_array($a[$j], $keywords)) {
					$label = "<div>{$a[$j]}</div>";
				}
			}
			if ($frame || $label !== "") {
				$tag = "<div class=\"photo-frame\">$tag$label</div>";
			}

			$text = str_replace($matches[0][$i], $tag, $text);
		}
	}
	//var_dump($matches);

	return $text;
}


function find_server_feed_id()
{
	global $server_name;
	global $server_feed_id;

	$http = "http://$server_name/atom";
	$https = "https://$server_name/atom";

	$row = sql("select feed_id from feed where uri = ? or uri = ?", $http, $https);
	if (count($row) > 0) {
		$server_feed_id = $row[0]["feed_id"];
	}
}


function similar_count($story)
{
	global $server_feed_id;

	if ($server_feed_id == 0) {
		find_server_feed_id();
	}

	$keywords = $story["keywords"];
	if (array_key_exists("publish_time", $story)) {
		$publish_time = $story["publish_time"];
	} else {
		$publish_time = $story["time"];
	}
	$beg_time = $publish_time - DAYS * 15;
	$end_time = $publish_time + DAYS * 15;

	$row = sql("select count(*) as item_count from article where match (title) against (? in boolean mode) and publish_time > ? and publish_time < ? and article.feed_id <> $server_feed_id", $keywords, $beg_time, $end_time);

	return $row[0]["item_count"];
}


function http_cache($url)
{
	global $redirect_url;
	global $now;

	$url_hash = crypt_sha256($url);
	$url = string_clean($url, "[a-z][A-Z][0-9]~#%&()-_+=[];:./?", 200);
	$redirect_url = "";

	if ($url === "") {
		return false;
	}

	$cache = db_find_rec("cache", ["url_hash" => $url_hash]);
	if ($cache === false) {
		$timeout = 5;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$data = curl_exec($ch);
		$redirect_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		if ($redirect_url === $url) {
			$redirect_url = "";
		}
		curl_close($ch);

		//$data = http_slurp($url);
		$hash = drive_set($data);
		if ($hash === false) {
			return false;
		}

		$cache = db_new_rec("cache");
		$cache["data_hash"] = $hash;
		$cache["url"] = $url;
		$cache["url_hash"] = $url_hash;
		db_set_rec("cache", $cache);
	} else {
		//writeln("drive_get [" . $cache["hash"] . "]");
		//var_dump($cache);
		$data = drive_get($cache["data_hash"]);
		$cache["access_time"] = $now;
		db_set_rec("cache", $cache);
	}

	return $data;
}


function public_path($time)
{
	return "/pub/" . gmdate("Y", $time) . "/" . gmdate("m", $time) . "/" . gmdate("d", $time);
}


function format_money($cents)
{
	return number_format((int) $cents / 100, 2);
}


function icon_list($require_16, $require_32, $require_64)
{
	global $doc_root;

	$data = fs_slurp("$doc_root/www/style.css");
	$icons = [];

	if ($require_16) {
		preg_match_all("/\.([a-z-]+)-16 {/", $data, $out);
		for ($i = 0; $i < count($out[1]); $i++) {
			$icon = $out[1][$i];
			if ($icon != "icon") {
				$icons[$icon]["16"] = true;
			}
		}
	}
	if ($require_32) {
		preg_match_all("/\.([a-z-]+)-32 {/", $data, $out);
		for ($i = 0; $i < count($out[1]); $i++) {
			$icon = $out[1][$i];
			if ($icon != "icon") {
				$icons[$icon]["32"] = true;
			}
		}
	}
	if ($require_64) {
		preg_match_all("/\.([a-z-]+)-64 {/", $data, $out);
		for ($i = 0; $i < count($out[1]); $i++) {
			$icon = $out[1][$i];
			if ($icon != "icon") {
				$icons[$icon]["64"] = true;
			}
		}
	}

	$a = [];
	$k = array_keys($icons);
	for ($i = 0; $i < count($icons); $i++) {
		$icon = $k[$i];
		if ($require_16) {
			$has_16 = array_key_exists("16", $icons[$icon]);
		} else {
			$has_16 = true;
		}
		if ($require_32) {
			$has_32 = array_key_exists("32", $icons[$icon]);
		} else {
			$has_32 = true;
		}
		if ($require_64) {
			$has_64 = array_key_exists("64", $icons[$icon]);
		} else {
			$has_64 = true;
		}
		if ($has_16 && $has_32 && $has_64) {
			$a[] = $icon;
		}
	}

	return $a;
}


function get_os_id($user_agent = "")
{
	if ($user_agent == "") {
		$user_agent = $_SERVER["HTTP_USER_AGENT"];
	}

	if (string_has($user_agent, "Android")) {
		return TYPE_ANDROID;
	} else if (string_has($user_agent, "CrOS")) {
		return TYPE_CHROME_OS;
	} else if (string_has($user_agent, "FreeBSD")) {
		return TYPE_FREEBSD;
	} else if (string_has($user_agent, "iPad")) {
		return TYPE_IPAD;
	} else if (string_has($user_agent, "iPhone")) {
		return TYPE_IPHONE;
	} else if (string_has($user_agent, "Linux")) {
		return TYPE_LINUX;
	} else if (string_has($user_agent, "Macintosh")) {
		return TYPE_MAC;
	} else if (string_has($user_agent, "Windows")) {
		return TYPE_WINDOWS;
	} else {
		return TYPE_UNKNOWN;
	}
}


function get_agent_id($user_agent = "")
{
	if ($user_agent == "") {
		$user_agent = $_SERVER["HTTP_USER_AGENT"];
	}

	if (string_has($user_agent, "Chrome") || string_has($user_agent, "CriOS")) {
		return TYPE_CHROME;
	} else if (string_has($user_agent, "PaleMoon")) {
		return TYPE_PALEMOON;
	} else if (string_has($user_agent, "Firefox")) {
		return TYPE_FIREFOX;
	} else if (string_has($user_agent, "MSIE")) {
		return TYPE_IE;
	} else if (string_has($user_agent, "Pipecode")) {
		return TYPE_PIPECODE;
	} else if (string_has($user_agent, "Pipedot")) {
		return TYPE_PIPEDOT;
	} else if (string_has($user_agent, "Safari")) {
		return TYPE_SAFARI;
	} else {
		return TYPE_UNKNOWN;
	}
}


function get_country_id($country_code, $country_name = "")
{
	$country = db_find_rec("country", ["country_code" => $country_code]);
	if ($country === false) {
		$country = db_new_rec("country");
		$country["country_code"] = $country_code;
		$country["country_name"] = $country_name;
		db_set_rec("country", $country);
		return db_last();
	}

	return $country["country_id"];
}


function get_ip_id($remote_ip = "")
{
	if ($remote_ip == "") {
		if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			$remote_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else {
			$remote_ip = $_SERVER["REMOTE_ADDR"];
		}
	}

	$ip = db_find_rec("ip", ["address" => $remote_ip]);
	if ($ip === false) {
		$geo = geo_ip($remote_ip);
		$country_id = get_country_id($geo["country_code"], $geo["country"]);

		$ip = db_new_rec("ip");
		$ip["address"] = $remote_ip;
		$ip["country_id"] = $country_id;
		$ip["latitude"] = $geo["latitude"];
		$ip["longitude"] = $geo["longitude"];
		db_set_rec("ip", $ip);
		return db_last();
	}

	return $ip["ip_id"];
}


function human_diff($diff, $full = false)
{
	if ($full) {
		$n = abs($diff);

		$seconds = $n % 60;
		$minutes = round($n / 60) % 60;
		$hours = round($n / (60 * 60)) % 24;
		$days = round($n / (60 * 60 * 24));

		return "$days day" . ($days == 1 ? "" : "s") . " $hours hour" . ($hours == 1 ? "" : "s") . " $minutes minute" . ($minutes == 1 ? "" : "s") . " $seconds second" . ($seconds == 1 ? "" : "s") . "";
	}

	$a = array("sec", "min", "hour", "day", "year");
	$b = array(60, 60, 24, 365, 1);
	$n = $diff;

	if ($diff > 315360000) {
		return "never";
	}
	for ($i = 0; $i < count($a); $i++) {
		if ($n < $b[$i] || $i == 4) {
			$n = floor($n);
			if ($n == 1) {
				return $n . " " . $a[$i];
			} else {
				return $n . " " . $a[$i] . "s";
			}
		}
		$n /= $b[$i];
	}
}


function load_server_conf()
{
	global $server_conf;
	global $auth_key;
	global $auth_expire;
	global $captcha_key;
	global $https_enabled;
	global $https_redirect_enabled;
	global $http_host;
	global $server_name;
	global $server_redirect_enabled;
	global $server_slogan;
	global $server_title;
	global $smtp_server;
	global $smtp_port;
	global $smtp_address;
	global $smtp_username;
	global $smtp_password;
	global $twitter_enabled;
	global $oauth_token;
	global $oauth_token_secret;

	$server_conf = db_get_conf("server_conf");

	$auth_key = $server_conf["auth_key"];
	if ($auth_key == "") {
		$auth_key = random_hash();
		$server_conf["auth_key"] = $auth_key;
		db_set_conf("server_conf", $server_conf);
	}
	$auth_expire = 1 * YEARS;

	$captcha_key = $server_conf["captcha_key"];

	$https_enabled = (bool) $server_conf["https_enabled"];
	$https_redirect_enabled = (bool) $server_conf["https_redirect_enabled"];

	$server_name = $server_conf["server_name"];
	if ($server_name == "example.com" || $server_name == "") {
		$server_name = $http_host;
		$server_conf["server_name"] = $server_name;
		db_set_conf("server_conf", $server_conf);
	}
	$server_redirect_enabled = (bool) $server_conf["server_redirect_enabled"];
	$server_slogan = $server_conf["server_slogan"];
	$server_title = $server_conf["server_title"];

	$smtp_server = $server_conf["smtp_server"];
	$smtp_port = $server_conf["smtp_port"];
	$smtp_address = $server_conf["smtp_address"];
	$smtp_username = $server_conf["smtp_username"];
	$smtp_password = $server_conf["smtp_password"];

	$twitter_enabled = (bool) $server_conf["twitter_enabled"];
	define('CONSUMER_KEY', $server_conf["twitter_consumer_key"]);
	define('CONSUMER_SECRET', $server_conf["twitter_consumer_secret"]);
	define('OAUTH_CALLBACK', '');
	$oauth_token = $server_conf["twitter_oauth_token"];
	$oauth_token_secret = $server_conf["twitter_oauth_secret"];

	date_default_timezone_set($server_conf["time_zone"]);
}


function require_feature($key)
{
	global $server_conf;

	if (!$server_conf[$key . "_enabled"]) {
		$feature = ucwords($key);
		fatal("$feature Feature Disabled", "error", "Feature Disabled", "The $feature system is disabled on this server.");
	}
}


function require_admin()
{
	global $auth_user;

	if (!$auth_user["admin"]) {
		fatal("Access Denied", "lock", "Access Denied", "You need administrative rights to access this page.");
	}
}


function require_editor()
{
	global $auth_user;

	if (!$auth_user["editor"]) {
		fatal("Access Denied", "lock", "Access Denied", "You need editor rights to access this page.");
	}
}


function require_developer()
{
	global $auth_user;

	if (!$auth_user["developer"]) {
		fatal("Access Denied", "lock", "Access Denied", "You need developer rights to access this page.");
	}
}


function require_login()
{
	global $auth_zid;
	global $http_host;
	global $https_enabled;
	global $request_uri;
	global $server_name;

	if ($auth_zid === "") {
		if ($https_enabled) {
			$protocol = "https";
		} else {
			$protocol = "http";
		}
		if ($http_host != $server_name) {
			$server = "&server=$http_host";
		} else {
			$server = "";
		}
		fatal("Login Required", "user", "Login Required", "You must <a href=\"$protocol://$server_name/login?referer=$request_uri$server\">login</a> to access this page.");
	}
}


function require_mine($test = -1)
{
	global $auth_zid;
	global $zid;

	require_login();
	if ($test === -1) {
		if ($zid !== $auth_zid) {
			fatal("Not Your Page", "important", "Not Your Page", "This is not your page.");
		}
	} else if ($test !== $auth_zid) {
		fatal("Not Your Item", "important", "Not Your Item", "This is not your item.");
	}
}


function fatal($title, $icon = "error", $name = "", $value = "", $code = 200)
{
	if ($code != 200) {
		http_response_code($code);
	}
	if ($name == "" && $value == "") {
		$name = "Fatal Error";
		$value = $title;
		$title = "$name - $title";
	}

	print_header($title);
	beg_main();
	writeln('<div class="balloon">');
	writeln('	<dl class="dl-32 ' . $icon . '-32">');
	writeln('		<dt>' . $name . '</dt>');
	writeln('		<dd>' . $value . '</dd>');
	writeln('	</dl>');
	writeln('</div>');
	end_main();
	print_footer();
	die();
}


$request_uri = $_SERVER["REQUEST_URI"];
if (string_has($request_uri, "?")) {
	$request_script = substr($request_uri, 0, strpos($request_uri, "?"));
} else {
	$request_script = $request_uri;
}

$a = parse_url($request_uri);
if (array_key_exists("query", $a)) {
	$query = $a["query"];
} else {
	$query = "";
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

if (array_key_exists("HTTP_HOST", $_SERVER)) {
	$http_host = $_SERVER["HTTP_HOST"];
} else {
	$http_host = gethostname();
}
load_server_conf();
if ($https_redirect_enabled && $protocol != "https") {
	header("Location: https://$server_name$request_uri");
	die();
}
$user_page = "";
$meta = "";
$mine = false;
$a = explode(".", $server_name);
$server_level = count($a);
$a = explode(".", $http_host);
if (count($a) == $server_level) {
	if ($server_redirect_enabled && $http_host != $server_name) {
		header("Location: $protocol://$server_name$request_uri");
		die();
	}
} else if (count($a) == $server_level + 1) {
	if ($server_redirect_enabled && $a[1] . "." . $a[2] != $server_name) {
		header("Location: $protocol://" . $a[0] . ".$server_name$request_uri");
		die();
	}
	if ($server_redirect_enabled && $a[0] == "www") {
		header("Location: $protocol://$server_name$request_uri");
		die();
	}
	$user_page = strtolower($a[0]);
	if (!string_uses($user_page, "[a-z][0-9]")) {
		fatal("Invalid user page");
	}
	if (!is_local_user("$user_page@$server_name")) {
		fatal("User not found");
	}
}
if ($user_page != "") {
	$zid = "$user_page@$server_name";
	$user_conf = db_get_conf("user_conf", $zid);
}

check_auth();

if ($auth_zid != "") {
	date_default_timezone_set($auth_user["time_zone"]);
	$lang = $auth_user["lang"];
	include("$doc_root/lang/$lang.php");
	if ($user_page != "") {
		$mine = ($zid === $auth_zid);
	}
}
