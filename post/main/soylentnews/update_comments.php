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
set_time_limit(0);

$start = http_post_int("start");
$max_items = http_post_int("max_items");
$max_pages = http_post_int("max_pages");
$force = http_post_bool("force");

import_comments($start, $max_items, $max_pages, $force);

writeln("done");
