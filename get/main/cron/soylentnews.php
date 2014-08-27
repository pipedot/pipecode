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

header("Content-Type: text/plain");
header_expires();
date_default_timezone_set("UTC");
set_time_limit(14 * 60);


//$zid = "bryan-1@pipedot.net";
//$time = time();
//		$comment_id = str_replace("-", "_", $import_server_name);
//		$comment_id = str_replace(".", "_", $comment_id);
//		$comment_id = substr(create_id($zid, $time), 0, -1 * strlen($server_name)) . $comment_id;
//die($comment_id);

//import_comment(64779, 2744);
//import_comment(66440, 2744);
import_stories(0, -1, 50, false);
import_comments(0, -1, 50, false);
//import_stories();
//import_story("14/07/10/1451201");
//import_story("14/07/11/0241252");
//import_story("14/07/05/1811259");
//print "uid [" . import_user("janrinok") . "]";
//add_user(29, "bryan");
//import_user("bryan");
//import_user("Jesus_666");
fix_comments();

writeln("done");
