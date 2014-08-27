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

include("clean.php");
include("soylentnews.php");

if (!$auth_user["admin"]) {
	die("not an admin");
}

header("Content-Type: text/plain");
header_expires();
date_default_timezone_set("UTC");

$username = http_post_string("username", array("len" => 20, "valid" => "[a-z][A-Z][0-9]\$_.+!*'(),- "));

import_user($username);

$soylentnews_user = db_get_rec("soylentnews_user", array("username" => $username));

writeln("username [" . $soylentnews_user["username"] . "]");
writeln("uid [" . $soylentnews_user["uid"] . "]");
writeln("zid [" . $soylentnews_user["zid"] . "]");
writeln();
writeln("done");
