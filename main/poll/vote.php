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

include("poll.php");

$qid = (int) $s2;
$poll_question = db_get_rec("poll_question", $qid);
$clean = clean_url($poll_question["question"]);
$type_id = $poll_question["type_id"];

if (http_post()) {
	if ($auth_zid == "") {
		print_header("Login to Vote");
		writeln('<h1>Login to Vote</h1>');
		writeln('<p><a href="/sign_in">Sign In</a></p>');
		writeln('<p><a href="/sign_up">Sign Up</a></p>');
		print_footer();
		die();
	}

	if ($type_id == 1) {
		$aid = http_post_int("aid");
		$poll_answer = db_get_rec("poll_answer", $aid);
		if ($qid != $poll_answer["qid"]) {
			die("answer [$aid] not on question [$qid]");
		}
	} else if ($type_id == 2) {
		$aids = @$_POST["aid"];
		for ($i = 0; $i < count($aids); $i++) {
			if (!string_uses($aids[$i], "[0-9]")) {
				die("invalid aid [" . $aids[$i] . "]");
			}
			$poll_answer = db_get_rec("poll_answer", $aids[$i]);
			if ($qid != $poll_answer["qid"]) {
				die("answer [" . $aids[$i] . "] not on question [$qid]");
			}
		}
	} else if ($type_id == 3) {
		$row = run_sql("select count(*) as answer_count from poll_answer where qid = ?", array($qid));
		$max = $row[0]["answer_count"];

		$aids = @$_POST["aid"];
		$keys = array_keys($aids);
		$scores = array();
		for ($i = 0; $i < count($keys); $i++) {
			if (!string_uses($keys[$i], "[0-9]")) {
				die("invalid aid [" . $keys[$i] . "]");
			}
			$poll_answer = db_get_rec("poll_answer", $keys[$i]);
			if ($qid != $poll_answer["qid"]) {
				die("answer [" . $keys[$i] . "] not on question [$qid]");
			}
			$aid = $keys[$i];
			$score = (int) $aids[$aid];
			if ($aids[$aid] === "0" || $score > $max) {
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

	if (db_has_rec("poll_vote", array("qid" => $qid, "zid" => $auth_zid))) {
		run_sql("delete from poll_vote where qid = ? and zid = ?", array($qid, $auth_zid));
	}

	if ($type_id == 1) {
		run_sql("insert into poll_vote (qid, aid, zid, time) values (?, ?, ?, ?)", array($qid, $aid, $auth_zid, time()));
	} else if ($type_id == 2) {
		for ($i = 0; $i < count($aids); $i++) {
			run_sql("insert into poll_vote (qid, aid, zid, time) values (?, ?, ?, ?)", array($qid, $aids[$i], $auth_zid, time()));
		}
	} else if ($type_id == 3) {
		for ($i = 0; $i < count($aids); $i++) {
			$aid = $keys[$i];
			if ($aids[$aid] === "") {
				$points = 0;
			} else {
				$points = $max + ((int) $aids[$aid]) * -1 + 1;
			}
			if ($points > 0) {
				run_sql("insert into poll_vote (qid, aid, zid, time, points) values (?, ?, ?, ?, ?)", array($qid, $aid, $auth_zid, time(), $points));
			}
		}
	}

	header("Location: /poll/$qid/$clean");
	die();
}

print_header("Poll");
print_left_bar("main", "poll");
beg_main("cell");

vote_box($qid, true, true);

end_main();
print_footer();
