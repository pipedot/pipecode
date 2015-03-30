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


function print_header($title = "", $link_name = array(), $link_icon = array(), $link_url = array(), $logo = "")
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
	global $meta;

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
	writeln('<meta charset="utf-8"/>');
	writeln('<meta name="viewport" content="width=device-width, initial-scale=1"/>');
	print $meta;
	writeln('<link rel="icon" href="/favicon.ico" sizes="16x16 32x32 48x48" type="image/x-icon"/>');
	//$theme = $server_conf["theme"];
	writeln('<link rel="stylesheet" href="/style.css?t=' . fs_time("$doc_root/www/style.css") . '"/>');
	//writeln('<link rel="stylesheet" href="/theme/text.css?t=' . fs_time("$doc_root/www/theme/text.css") . '"/>');
	if ($auth_user["large_text_enabled"]) {
		//die("[" . $user_conf["large_text_enabled"] . "]");
		writeln('<style>');
		writeln('html { font-size: 80%; }');
		writeln('</style>');
	}
	if ($request_script == "/") {
		writeln('<link rel="alternate" href="/atom" type="application/atom+xml" title="Stories">');
	}
	if ($auth_user["javascript_enabled"]) {
		writeln('<script src="/lib/jquery/jquery.js?t=' . fs_time("$doc_root/www/lib/jquery/jquery.js") . '"></script>');
		writeln('<script src="/lib/jquery/jquery-ui.js?t=' . fs_time("$doc_root/www/lib/jquery/jquery-ui.js") . '"></script>');
		writeln('<script src="/common.js?t=' . fs_time("$doc_root/www/common.js") . '"></script>');
	}

	writeln('</head>');
	writeln('<body>');

	writeln('<header class="title">');
	writeln('	<div><a class="logo-big" href="' . $protocol . '://' . $server_name . '/"></a><a class="logo-small" href="' . $protocol . '://' . $server_name . '/"></a></div>');

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

	writeln("	<div>");
	for ($i = 0; $i < count($link_name); $i++) {
		$name = $link_name[$i];
		$icon = "";
		$link = "";

		if ($name == "Submit") {
			$icon = "notepad";
			$link = "/submit";
		} else if ($name == "Home") {
			$icon = "home";
			$link = user_link($auth_zid) . "menu/";
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

		writeln("		<a href=\"$link\" class=\"icon-16 {$icon}-16\">$name</a>" . ($i == count($link_name) - 1 ? '' : ' | '));
	}
	writeln("	</div>");
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
			$section_link = array("story/", "pipe/", "poll/", "search", "topic/", "feed/", "stream/");
		} else {
			$section_name = array("stories", "pipe", "poll", "search", "topics", "stream");
			$section_link = array("story/", "pipe/", "poll/", "search", "topic/", "stream/");
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
			//writeln('	<a class="nav-active" href="' . $link . '">' . $section_name[$i] . '</a>');
			writeln('	<a class="active" href="' . $link . '">' . $section_name[$i] . '</a>');
		} else {
			//writeln('	<a class="nav-inactive" href="' . $link . '">' . $section_name[$i] . '</a>');
			writeln('	<a href="' . $link . '">' . $section_name[$i] . '</a>');
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
				writeln('	<a class="active" href="/topic/' . $topic . '">' . $topic . '</a>');
			} else {
				writeln('	<a href="/topic/' . $topic . '">' . $topic . '</a>');
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
					writeln('	<a class="active" href="/topic/' . $topic . '">' . $topic . '</a>');
				} else {
					writeln('	<a href="/topic/' . $topic . '">' . $topic . '</a>');
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
	$link = user_link($auth_zid);

	writeln('<div class="dialog-title">' . $auth_zid . '</div>');
	writeln('<div class="dialog-body">');
	writeln('<table class="user-box">');
	writeln('	<tr>');
	//writeln('		<td><a href="' . $link . 'comments"><div class="chat-32">Comments</div></a></td>');
	writeln('		<td><a class="icon-32 news-32" href="' . $link . 'feed/">Feed</a></td>');
	writeln('		<td><a class="icon-32 notepad-32" href="' . $link . 'journal/">Journal</a></td>');
	writeln('	</tr>');
//	writeln('	<tr>');
//	writeln('		<td><a class="icon-32 news-32" href="' . $link . 'karma/"><div class="user-box-icon" style="background-image: url(/images/karma-good-32.png)">Karma</div></a></td>');
//	writeln('		<td><a class="icon-32 news-32" href="' . $link . '"><div class="user-box-icon" style="background-image: url(/images/news-32.png)">Feed</div></a></td>');
//	writeln('	</tr>');
//	writeln('	<tr>');
//	writeln('		<td><a class="icon-32 news-32" href="' . $link . 'comments"><div class="user-box-icon" style="background-image: url(/images/chat-32.png)">Comments</div></a></td>');
//	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td><a class="icon-32 mail-32" href="' . $link . 'mail/">' . $mail . '</a></td>');
	writeln('		<td><a class="icon-32 tools-32" href="' . $link . 'profile/">Settings</a></td>');
	writeln('	</tr>');
//	writeln('	<tr>');
//	writeln('		<td><a class="icon-32 news-32" href="' . $link . 'stream/"><div class="internet-32">Stream</div></a></td>');
//	writeln('		<td><a class="icon-32 news-32" href="http://' . $auth_user["username"] . '.' . $server_name . '/friends/"><div class="user-box-icon" style="background-image: url(/images/users-32.png)">Friends</div></a></td>');
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
		$a["link"] = "$p://$server_name/pipe/" . crypt_crockford_encode($pipe["pipe_id"]);
	} else if ($comment["type"] == "poll") {
		$poll = db_get_rec("poll", $comment["root_id"]);
		$a["type"] = "poll";
		$a["title"] = $poll["question"];
		$a["link"] = "$p://$server_name/poll/" . gmdate("Y-m-d", $poll["publish_time"]) . "/" . $poll["slug"];
	} else if ($comment["type"] == "journal") {
		$journal = db_get_rec("journal", $comment["root_id"]);
		$a["type"] = "journal";
		$a["title"] = $journal["title"];
		if ($journal["published"]) {
			$a["link"] = user_link($journal["zid"]) . "journal/" . gmdate("Y-m-d", $journal["publish_time"]) . "/" . $journal["slug"];
		} else {
			$a["link"] = user_link($journal["zid"]) . "journal/" . crypt_crockford_encode($journal["journal_id"]);
		}
	} else if ($comment["type"] == "card") {
		$card = db_get_rec("card", array("card_id" => $comment["root_id"]));
		$a["type"] = "card";
		$a["title"] = "#" . crypt_crockford_encode($card["card_id"]);
		$a["link"] = "$p://$server_name/card/" . crypt_crockford_encode($card["card_id"]);
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


function create_short($type)
{
	$short = db_new_rec("short");
	$short["type"] = $type;
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


function count_comments($type = "", $root_id = "")
{
	global $auth_zid;
	global $auth_user;

	if ($type === "" && $root_id === "") {
		$comments["count"] = 0;
		$comments["label"] = " comments";
		$comments["new"] = 0;
		$comments["tag"] = "<b>0</b> comments";
		return;
	}

	$comments = array();
	if ($auth_user["show_junk_enabled"]) {
		$row = sql("select count(*) as comments from comment where type = ? and root_id = ?", $type, $root_id);
	} else {
		$row = sql("select count(*) as comments from comment where type = ? and root_id = ? and junk_status <= 0", $type, $root_id);
	}
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
			if ($auth_user["show_junk_enabled"]) {
				$row = sql("select count(*) as comments from comment where type = ? and root_id = ? and edit_time > ?", $type, $root_id, $time);
			} else {
				$row = sql("select count(*) as comments from comment where type = ? and root_id = ? and edit_time > ? and junk_status <= 0", $type, $root_id, $time);
			}
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


function print_comments($type, $rec)
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

	if ($auth_user["javascript_enabled"]) {
		print_sliders($type, $rec["{$type}_id"]);
		print_noscript();
	} else {
		render_page($type, $rec["{$type}_id"], false);
	}

	$last_seen = update_view_time($type, $rec["{$type}_id"]);

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
		//writeln('get_comments("' . $type . '", "' . $rec["{$type}_id"] . '");');
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
				$tag = "<div class=\"photo-frame\">$tag$label</div>";
			}

			$text = str_replace($matches[0][$i], $tag, $text);
		}
	}
	//var_dump($matches);

	return $text;
}


function similar_count($story)
{
	global $server_feed_id;

	$keywords = $story["keywords"];
	if (array_key_exists("publish_time", $story)) {
		$publish_time = $story["publish_time"];
	} else {
		$publish_time = $story["time"];
	}
	$beg_time = $publish_time - 86400 * 15;
	$end_time = $publish_time + 86400 * 15;

	$row = sql("select count(*) as item_count from article where match (title) against (? in boolean mode) and publish_time > ? and publish_time < ? and article.feed_id <> $server_feed_id", $keywords, $beg_time, $end_time);

	return $row[0]["item_count"];
}


function http_cache($url)
{
	global $redirect_url;

	$cache_id = crypt_sha256($url);
	$url = string_clean($url, "[a-z][A-Z][0-9]~#%&()-_+=[];:./?", 200);
	$redirect_url = "";

	if ($url === "") {
		return false;
	}

	$cache = db_find_rec("cache", $cache_id);
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
		$cache["cache_id"] = $cache_id;
		$cache["hash"] = $hash;
		$cache["url"] = $url;
		db_set_rec("cache", $cache);
	} else {
		//writeln("drive_get [" . $cache["hash"] . "]");
		//var_dump($cache);
		$data = drive_get($cache["hash"]);
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

$server_conf = db_get_conf("server_conf");
if (array_key_exists("HTTP_HOST", $_SERVER)) {
	$http_host = $_SERVER["HTTP_HOST"];
} else {
	$http_host = $server_name;
}
$user_page = "";
$meta = "";
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
