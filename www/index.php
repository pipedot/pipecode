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

include("../include/common.php");

if (!string_uses($request_script, "[A-Z][a-z][0-9]_-./")) {
	die("invalid request [$request_script]");
}

$a = explode("/", $request_script);
if (count($a) >= 2) {
	$s1 = $a[1];
} else {
	$s1 = "";
}
if (count($a) >= 3) {
	$s2 = $a[2];
} else {
	$s2 = "";
}
if (count($a) >= 4) {
	$s3 = $a[3];
} else {
	$s3 = "";
}
if (count($a) >= 5) {
	$s4 = $a[4];
} else {
	$s4 = "";
}

if (http_post()) {
	$root = "$doc_root/post/";
} else {
	$root = "$doc_root/get/";
}
if ($user_page == "") {
	$root .= "main";
} else {
	$root .= "user";
}

if ($s1 == "") {
	include("$root/index.php");
	die();
}
if (fs_is_file("$root/$s1.php")) {
	include("$root/$s1.php");
	die();
}
if (fs_is_dir("$root/$s1")) {
	if ($s2 == "") {
		if (fs_is_file("$root/$s1/index.php")) {
			include("$root/$s1/index.php");
			die();
		}
	} else {
		if (fs_is_file("$root/$s1/$s2.php")) {
			include("$root/$s1/$s2.php");
			die();
		}
		if ($s3 != "" && fs_is_file("$root/$s1/$s3.php")) {
			include("$root/$s1/$s3.php");
			die();
		}
		if (fs_is_file("$root/$s1/root.php")) {
			include("$root/$s1/root.php");
			die();
		}
	}
}
if (fs_is_dir("$root/$s1/$s2")) {
	if ($s3 == "") {
		if (fs_is_file("$root/$s1/$s2/index.php")) {
			include("$root/$s1/$s2/index.php");
			die();
		}
	} else {
		if (fs_is_file("$root/$s1/$s2/$s3.php")) {
			include("$root/$s1/$s2/$s3.php");
			die();
		}
		if ($s4 != "" && fs_is_file("$root/$s1/$s2/$s4.php")) {
			include("$root/$s1/$s2/$s4.php");
			die();
		}
		if (fs_is_file("$root/$s1/$s2/root.php")) {
			include("$root/$s1/$s2/root.php");
			die();
		}
	}
}

$slug = substr($request_uri, 1);
if (string_uses($slug, "[A-Z][a-z][0-9]-_.")) {
	if (db_has_rec("page", $slug)) {
		$page = db_get_rec("page", $slug);
		print_header($page["title"]);
		//writeln('<hr/>');
		beg_main("static");
		//writeln('<div style="padding-top: 4px; margin-bottom: 8px;">');
		writeln($page["body"]);
		//writeln('</div>');
		end_main();
		print_footer();
		die();
	}
}

if (string_uses($slug, "[A-Z][a-z][0-9]")) {
	$short_id = crypt_crockford_decode($slug);
	//die("short_id [$short_id]");
	if (db_has_rec("short", $short_id)) {
		$short = db_get_rec("short", $short_id);
		//die("type [" . $short["type"] . "] item_id [" . $short["item_id"] . "]");

		$short_view = array();
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
		if (empty($_SERVER["REMOTE_ADDR"])) {
			$short_view["remote_ip"] = "";
		} else {
			$short_view["remote_ip"] = $_SERVER["REMOTE_ADDR"];
		}
		$short_view["zid"] = $auth_zid;
		$short_view["time"] = time();
		//var_dump($short_view);
		//die();
		db_set_rec("short_view", $short_view);

		header("Location: " . item_link($short["type"], $short["item_id"]));
		die();
	}
}

http_response_code(404);
print_header();
beg_main();

writeln('<h1>404</h1>');
writeln('request_uri [' . $request_uri . ']');

end_main();
print_footer();
