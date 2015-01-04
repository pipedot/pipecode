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

include("../include/common.php");
include("short.php");
include("geoip.php");

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
if ($user_page === "") {
	$root .= "main";
} else {
	$root .= "user";
}

if ($s1 === "drive") {
	if (!string_uses($request_script, "[A-Z][a-z][0-9]`~!@#\$%^&()_+-=[]{};',./ ")) {
		die("invalid drive request [$request_script]");
	}
	include("drive.php");
	$path = decode_file_name(substr($request_script, 6));
	if (string_uses($query, "[a-z]")) {
		if (fs_is_file("$root/$s1/$query.php")) {
			include("$root/$s1/$query.php");
			die();
		} else {
			die("unknown action [$query]");
		}
	}
	if (substr($request_script, -1) !== "/") {
		header("Location: $request_script/");
		die();
	}
	include("$root/$s1/index.php");
	die();
} else if (!string_uses($request_script, "[A-Z][a-z][0-9]_-./+")) {
	die("invalid request [$request_script]");
}

if ($s1 === "") {
	include("$root/index.php");
	die();
}
if (fs_is_file("$root/$s1.php")) {
	include("$root/$s1.php");
	die();
}
if (fs_is_dir("$root/$s1")) {
	if ($s2 === "") {
		if (fs_is_file("$root/$s1/index.php")) {
			include("$root/$s1/index.php");
			die();
		}
	} else {
		if (fs_is_file("$root/$s1/$s2.php")) {
			include("$root/$s1/$s2.php");
			die();
		}
		if ($s3 !== "" && fs_is_file("$root/$s1/$s3.php")) {
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
	if ($s3 === "") {
		if (fs_is_file("$root/$s1/$s2/index.php")) {
			include("$root/$s1/$s2/index.php");
			die();
		}
	} else {
		if (fs_is_file("$root/$s1/$s2/$s3.php")) {
			include("$root/$s1/$s2/$s3.php");
			die();
		}
		if ($s4 !== "" && fs_is_file("$root/$s1/$s2/$s4.php")) {
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
	short_redirect($slug);
}

if (substr($slug, -1) === "+") {
	$slug = substr($slug, 0, -1);
	if (string_uses($slug, "[A-Z][a-z][0-9]")) {
		short_info($slug);
	}
}

http_response_code(404);
print_header();
beg_main();

writeln('<h1>404</h1>');
writeln('request_uri [' . $request_uri . ']');

end_main();
print_footer();
