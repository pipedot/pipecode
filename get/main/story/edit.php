<?
//
// Pipecode - distributed social network
// Copyright (C) 2014-2015 Bryan Beicker <bryan@pipedot.org>
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

include("clean.php");
include("story.php");

if (!$auth_user["editor"]) {
	die("you are not an editor");
}

$story = item_request("story");
$zid = $story["author_zid"];
$title = $story["title"];
$tid = $story["tid"];
$icon = $story["icon"];
$clean_body = $story["body"];
$dirty_body = dirty_html($clean_body);

print_story_box($story["story_id"], $tid, $icon, $title, $clean_body, $dirty_body, $zid);
