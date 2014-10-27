<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
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

if (!$auth_user["admin"]) {
	die("not an admin");
}

$question = http_post_string("question", array("len" => 200));
$type_id = http_post_int("type_id");
$answer = $_POST["answer"];

$time = time();
$poll = db_new_rec("poll");
$poll["poll_id"] = create_id($auth_zid, $time);
$poll["question"] = clean_text($question);
$poll["short_id"] = create_short("poll", $poll["poll_id"]);
$poll["slug"] = clean_url($question);
$poll["type_id"] = $type_id;
$poll["zid"] = $auth_zid;
db_set_rec("poll", $poll);

for ($i = 0; $i < count($answer); $i++) {
	$poll_answer = array();
	$poll_answer["answer_id"] = create_id("", $time);
	$poll_answer["poll_id"] = $poll["poll_id"];
	$poll_answer["answer"] = clean_text($answer[$i]);
	$poll_answer["position"] = $i;
	db_set_rec("poll_answer", $poll_answer);
}

header("Location: /menu/");
