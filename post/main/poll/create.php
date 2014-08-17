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

if (!$auth_user["admin"]) {
	die("not an admin");
}

$question = http_post_string("question", array("len" => 200));
$type_id = http_post_int("type_id");
$last_row = http_post_int("last_row");
$a = array();
for ($i = 0; $i <= $last_row; $i++) {
	$answer = http_post_string("answer_$i", array("len" => 200, "required" => false));
	if ($answer != "") {
		$a[] = $answer;
	}
}

$time = time();
$poll = array();
$poll["poll_id"] = create_id($auth_zid, $time);
$poll["question"] = $question;
$poll["short_id"] = create_short("poll", $poll["poll_id"]);
$poll["slug"] = clean_url($question);
$poll["time"] = $time;
$poll["type_id"] = $type_id;
$poll["zid"] = $auth_zid;
db_set_rec("poll", $poll);

for ($i = 0; $i < count($a); $i++) {
	$poll_answer = array();
	$poll_answer["answer_id"] = create_id("", $time);
	$poll_answer["poll_id"] = $poll["poll_id"];
	$poll_answer["answer"] = $a[$i];
	$poll_answer["position"] = $i;
	db_set_rec("poll_answer", $poll_answer);
}

header("Location: /menu/");
