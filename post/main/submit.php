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
include("story.php");
include("captcha.php");
include("submit.php");

if (!$server_conf["submit_enabled"]) {
	die("sumbit not enabled");
}

$title = clean_subject();
list($clean_body, $dirty_body) = clean_body();
$tid = http_post_int("tid");
$answer = http_post_string("answer", array("required" => false));
$time = time();

if ($auth_zid == "" && !captcha_verify($answer)) {
	die("captcha failed");
}

$topic = db_get_rec("topic", $tid);

if (http_post("preview")) {
	print_submit_box($title, $dirty_body, $clean_body, $tid, true);
	die();
}

$pipe = array();
$pipe["pipe_id"] = create_id($auth_zid, $time);
$pipe["author_zid"] = $auth_zid;
$pipe["body"] = $clean_body;
$pipe["closed"] = 0;
$pipe["edit_zid"] = "";
$pipe["icon"] = $topic["icon"];
$pipe["reason"] = "";
$pipe["short_id"] = create_short("pipe", $pipe["pipe_id"]);
$pipe["slug"] = clean_url($title);
$pipe["tid"] = $tid;
$pipe["title"] = $title;
$pipe["time"] = $time;
db_set_rec("pipe", $pipe);

header("Location: /pipe/" . $pipe["pipe_id"]);
