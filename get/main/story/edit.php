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
$story = db_get_rec("story", $story_id);
$zid = $story["author_zid"];
$title = $story["title"];
$tid = $story["tid"];
$icon = $story["icon"];
$clean_body = $story["body"];
$dirty_body = dirty_html($clean_body);

print_story_box($story_id, $tid, $icon, $title, $clean_body, $dirty_body, $zid);
