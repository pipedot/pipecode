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

$sid_date = http_post_string("sid_date", array("len" => 20, "valid" => "[0-9]/"));

import_story($sid_date);

$soylentnews_story = db_get_rec("soylentnews_story", array("sid_date" => $sid_date));

writeln("sid [" . $soylentnews_story["sid"] . "]");
writeln("sid_date [" . $soylentnews_story["sid_date"] . "]");
writeln("story_id [" . $soylentnews_story["story_id"] . "]");
writeln("tid [" . $soylentnews_story["tid"] . "]");
writeln("time [" . date("Y-m-d H:i", $soylentnews_story["time"]) . "]");
writeln("title [" . $soylentnews_story["title"] . "]");
writeln("uid [" . $soylentnews_story["uid"] . "]");
writeln();
writeln($soylentnews_story["body"]);
writeln();
writeln("done");

