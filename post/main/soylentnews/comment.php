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

$cid = http_post_int("cid");
$sid = http_post_int("sid");

import_comment($cid, $sid);

$soylentnews_comment = db_get_rec("soylentnews_comment", array("cid" => $cid));

writeln("cid [" . $soylentnews_comment["cid"] . "]");
writeln("sid [" . $soylentnews_comment["sid"] . "]");
writeln("comment_id [" . $soylentnews_comment["comment_id"] . "]");
writeln("parent [" . $soylentnews_comment["parent"] . "]");
writeln("rating [" . $soylentnews_comment["rating"] . "]");
writeln("score [" . $soylentnews_comment["score"] . "]");
writeln("subject [" . $soylentnews_comment["subject"] . "]");
writeln("time [" . date("Y-m-d H:i", $soylentnews_comment["time"]) . "]");
writeln("uid [" . $soylentnews_comment["uid"] . "]");
writeln();
writeln($soylentnews_comment["body"]);
writeln();
writeln("done");

