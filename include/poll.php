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

function vote_box($qid, $full, $vote)
{
	global $poll_question;

	if (empty($poll_question)) {
		$poll_question = db_get_rec("poll_question", $qid);
	}
	$clean = clean_url($poll_question["question"]);
	$type_id = $poll_question["type_id"];
	writeln('<div class="dialog_title">Poll</div>');
	writeln('<div class="dialog_body">');

	$poll_answer = db_get_list("poll_answer", "position", array("qid" => $poll_question["qid"]));
	$k = array_keys($poll_answer);

	if ($vote) {
		//writeln('	<form action="/poll/' . $qid . '/vote" method="post">');
		beg_form("/poll/$qid/vote");
		writeln('	<div class="poll_question">' . $poll_question["question"] . '</div>');

		writeln('	<table class="poll_table">');
		for ($i = 0; $i < count($poll_answer); $i++) {
			$answer = $poll_answer[$k[$i]];
			writeln('		<tr>');
			if ($type_id == 1) {
				$units = "votes";
				writeln('			<td><input id="a_' . $answer["aid"] . '" name="aid" value="' . $answer["aid"] . '" type="radio"/></td>');
			} else if ($type_id == 2) {
				$units = "votes";
				writeln('			<td><input id="a_' . $answer["aid"] . '" name="aid[]" value="' . $answer["aid"] . '" type="checkbox"/></td>');
			} else if ($type_id == 3) {
				$units = "points";
				writeln('			<td><input id="a_' . $answer["aid"] . '" name="aid[' . $answer["aid"] . ']" type="text"/></td>');
			} else {
				die("unknown poll type [$type_id]");
			}
			writeln('			<td><label for="a_' . $answer["aid"] . '">' . $answer["answer"] . '</label></td>');
			writeln('		</tr>');
		}
		writeln('	</table>');

		if ($type_id == 1 || $type_id == 2) {
			$row = run_sql("select count(zid) as votes from poll_vote where qid = ?", array($qid));
			$votes = $row[0]["votes"];
		} else {
			$row = run_sql("select sum(points) as votes from poll_vote where qid = ?", array($qid));
			$votes = (int) $row[0]["votes"];
		}
		$row = run_sql("select count(cid) as comments from comment where qid = ?", array($qid));
		$comments = $row[0]["comments"];

		writeln('	<table class="fill">');
		writeln('		<tr>');
		writeln('			<td style="width: 40px"><input type="submit" value="Vote"/></td>');
		writeln('			<td><a href="/poll/' . $qid . '/' . $clean . '"><b>' . $comments . '</b> comments</a></td>');
		writeln('			<td class="right"><b>' . $votes . '</b> ' . $units . '</td>');
		writeln('		</tr>');
		writeln('	</table>');

		//writeln('	</form>');
		end_form();
	} else {
		$total = 0;
		$votes = array();
		writeln('	<table style="width: 100%">');
		writeln('		<tr>');
		writeln('			<td class="poll_question">' . $poll_question["question"] . '</td>');
		writeln('		</tr>');

		if ($type_id == 1 || $type_id == 2) {
			$units = "votes";
			for ($i = 0; $i < count($poll_answer); $i++) {
				$answer = $poll_answer[$k[$i]];

				$row = run_sql("select count(*) as votes from poll_vote where qid = ? and aid = ?", array($qid, $answer["aid"]));
				$votes[] = $row[0]["votes"];
				$total += $row[0]["votes"];
			}
		} else if ($type_id == 3) {
			$units = "points";
			for ($i = 0; $i < count($poll_answer); $i++) {
				$answer = $poll_answer[$k[$i]];

				$row = run_sql("select sum(points) as votes from poll_vote where qid = ? and aid = ?", array($qid, $answer["aid"]));
				$votes[] = $row[0]["votes"];
				$total += $row[0]["votes"];
			}
		}

		for ($i = 0; $i < count($poll_answer); $i++) {
			$answer = $poll_answer[$k[$i]];
			if ($total == 0) {
				$percent = 0;
			} else {
				$percent = round(($votes[$i] / $total) * 100);
			}

			writeln('		<tr>');
			writeln('			<td class="poll_answer">' . $answer["answer"] . '</td>');
			writeln('		</tr>');
			writeln('		<tr>');
			writeln('			<td><table class="poll_result"><tr><th style="width: ' . $percent . '%"></th><td style="width: ' . (100 - $percent) . '%">' . $votes[$i] . " $units ($percent%)" . '</td></tr></table></td>');
			writeln('		</tr>');
		}
		writeln('	</table>');

		writeln('	<table class="fill">');
		writeln('		<tr>');
		writeln('			<td><a href="/poll/' . $qid . '/vote"><b>Vote</b></a></td>');
		writeln('			<td class="right"><b>' . $total . '</b> ' . $units . '</td>');
		writeln('		</tr>');
		writeln('	</table>');
	}
	writeln('</div>');
}

