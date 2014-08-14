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

if (!@$auth_user["editor"]) {
	die("you are not an editor");
}

$story_id = $s2;
if (!string_uses($story_id, "[a-z][0-9]_")) {
	die("invalid story_id [$story_id]");
}
$story = db_get_rec("story", $story_id);
$zid = $story["author_zid"];

$title = clean_subject();
list($clean_body, $dirty_body) = clean_body(true, "story");
$icon = http_post_string("icon", array("len" => 50, "valid" => "[a-z][0-9]-_"));
$tid = http_post_int("tid");
$time = time();

if (http_post("publish")) {
	db_set_rec("story_edit", $story);

	$story["body"] = $clean_body;
	$story["edit_time"] = $time;
	$story["edit_zid"] = $auth_zid;
	$story["icon"] = $icon;
	$story["slug"] = clean_url($title);
	$story["tid"] = $tid;
	$story["title"] = $title;
	db_set_rec("story", $story);

	header("Location: /story/$story_id");
	die();
}

print_story_box($story_id, $tid, $icon, $title, $clean_body, $dirty_body, $zid);
