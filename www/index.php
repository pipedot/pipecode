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

if (!string_uses($request_script, "[a-z][0-9]_-/")) {
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

if ($user_page == "") {
	$root = "$doc_root/main";
} else {
	$root = "$doc_root/user";
}

if ($s1 == "") {
	include("$root/index.php");
	die();
}
if (fs_is_file("$root/$s1.php")) {
	include("$root/$s1.php");
	die();
} else if (fs_is_dir("$root/$s1")) {
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

http_response_code(404);
print_header();
beg_main();

writeln('<h1>404</h1>');
writeln('request_uri [' . $request_uri . ']');

end_main();
print_footer();
