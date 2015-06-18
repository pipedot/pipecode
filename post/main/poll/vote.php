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

include("poll.php");

$poll = item_request(TYPE_POLL);
$type_id = $poll["type_id"];

require_login();

if ($type_id == 1) {
	$answer_id = http_post_string("answer_id", array("len" => 64, "valid" => "[a-z][0-9]_"));
	$poll_answer = db_get_rec("poll_answer", $answer_id);
	if ($poll["poll_id"] != $poll_answer["poll_id"]) {
		die("answer [$answer_id] not on poll [" . $poll["poll_id"] . "]");
	}
} else if ($type_id == 2) {
	$answer_ids = @$_POST["answer_id"];
	for ($i = 0; $i < count($answer_ids); $i++) {
		if (!string_uses($answer_ids[$i], "[a-z][0-9]_")) {
			die("invalid answer_id [" . $answer_ids[$i] . "]");
		}
		$poll_answer = db_get_rec("poll_answer", $answer_ids[$i]);
		if ($poll["poll_id"] != $poll_answer["poll_id"]) {
			die("answer [" . $answer_ids[$i] . "] not on question [" . $poll["poll_id"] . "]");
		}
	}
} else if ($type_id == 3) {
	$row = sql("select count(*) as answer_count from poll_answer where poll_id = ?", $poll["poll_id"]);
	$max = $row[0]["answer_count"];

	$answer_ids = @$_POST["answer_id"];
	$keys = array_keys($answer_ids);
	$scores = array();
	for ($i = 0; $i < count($keys); $i++) {
		if (!string_uses($keys[$i], "[a-z][0-9]_")) {
			die("invalid answer_id [" . $keys[$i] . "]");
		}
		$poll_answer = db_get_rec("poll_answer", $keys[$i]);
		if ($poll["poll_id"] != $poll_answer["poll_id"]) {
			die("answer [" . $keys[$i] . "] not on question [" . $poll["poll_id"] . "]");
		}
		$answer_id = $keys[$i];
		$score = (int) $answer_ids[$answer_id];
		if ($answer_ids[$answer_id] === "0" || $score > $max) {
			die("score out of bounds [$score]");
		}
		if ($score > 0) {
			$scores[] = $score;
		}
	}
	if (count($scores) !== count(array_unique($scores))) {
		die("duplicate score detected");
	}
}

if (db_has_rec("poll_vote", array("poll_id" => $poll["poll_id"], "zid" => $auth_zid))) {
	sql("delete from poll_vote where poll_id = ? and zid = ?", $poll["poll_id"], $auth_zid);
}

if ($type_id == 1) {
	sql("insert into poll_vote (poll_id, answer_id, zid, time) values (?, ?, ?, ?)", $poll["poll_id"], $answer_id, $auth_zid, time());
} else if ($type_id == 2) {
	for ($i = 0; $i < count($answer_ids); $i++) {
		sql("insert into poll_vote (poll_id, answer_id, zid, time) values (?, ?, ?, ?)", $poll["poll_id"], $answer_ids[$i], $auth_zid, time());
	}
} else if ($type_id == 3) {
	for ($i = 0; $i < count($answer_ids); $i++) {
		$answer_id = $keys[$i];
		if ($answer_ids[$answer_id] === "") {
			$points = 0;
		} else {
			$points = $max + ((int) $answer_ids[$answer_id]) * -1 + 1;
		}
		if ($points > 0) {
			sql("insert into poll_vote (poll_id, answer_id, zid, time, points) values (?, ?, ?, ?, ?)", $poll["poll_id"], $answer_id, $auth_zid, time(), $points);
		}
	}
}

header("Location: /poll/" . gmdate("Y-m-d", $poll["publish_time"]) . "/" . $poll["slug"]);
