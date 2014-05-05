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

function print_pipe($pid)
{
	global $server_name;

	$pipe = db_get_rec("pipe", $pid);
	$date = date("Y-m-d H:i", $pipe["time"]);
	$topic = db_get_rec("topic", $pipe["tid"]);
	$a["zid"] = $pipe["zid"];
	$a["title"] = $pipe["title"];
	$a["icon"] = $pipe["icon"];
	$a["pid"] = $pipe["pid"];
	$a["topic"] = $topic["topic"];
	$a["story"] = $pipe["story"];
	$a["ipos"] = "middle";

	$row = run_sql("select count(cid) as comments from comment where pid = ?", array($pid));
	$a["comments"] = $row[0]["comments"];

	$row = run_sql("select sum(value) as score from pipe_vote where pid = ?", array($pid));
	$a["score"] = (int) $row[0]["score"];
	if ($a["score"] > 0) {
		$a["score"] = "+" . $a["score"];
	}

	print_article($a);
}


function print_pipe_small($pid, $full)
{
	global $server_name;
	global $auth_zid;
	global $auth_user;
	global $javascript_enabled;

	$pipe = db_get_rec("pipe", $pid);
	$date = date("Y-m-d H:i", $pipe["time"]);
	$score = 0;
	$topic = db_get_rec("topic", $pipe["tid"]);
	$zid = $pipe["zid"];
	if ($zid == "") {
		$by = "<b>Anonymous Coward</b>";
	} else {
		$by = "<b>$zid</b>";
	}

	$row = run_sql("select count(cid) as total from comment where pid = ?", array($pid));
	$total = $row[0]["total"];

	$row = run_sql("select value from pipe_vote where pid = ? and zid = ?", array($pid, $auth_zid));
	if (count($row) == 0) {
		$value = 0;
	} else {
		$value = $row[0]["value"];
	}

	$row = run_sql("select sum(value) as score from pipe_vote where pid = ?", array($pid));
	$score = (int) $row[0]["score"];
	if ($score > 0) {
		$score = "+$score";
	}

	if ($javascript_enabled) {
		writeln('<div id="title_' . $pid . '" class="pipe_title_collapse">');
	} else {
		writeln('<form method="post" action="/pipe/' . $pid . '/vote">');
		writeln('<div id="title_' . $pid . '" class="pipe_title_expand">');
	}
	writeln('<table class="fill">');
	writeln('	<tr>');
	if ($auth_zid != "") {
		if ($javascript_enabled) {
			if ($value < 0) {
				writeln('		<td style="width: 32px"><img id="icon_' . $pid . '_a" alt="You Voted Down" title="You Voted Down" style="cursor: pointer;" src="/images/down-32.png" onclick="vote(' . $pid . ', 1)"/></td>');
				writeln('		<td style="width: 32px"><img id="icon_' . $pid . '_b" alt="Undo Vote" title="Undo Vote" style="cursor: pointer;" src="/images/undo-32.png" onclick="vote(' . $pid . ', 0)"/></td>');
			} else if ($value == 0) {
				writeln('		<td style="width: 32px"><img id="icon_' . $pid . '_a" alt="Vote Up" title="Vote Up" style="cursor: pointer;" src="/images/add-32.png" onclick="vote(' . $pid . ', 1)"/></td>');
				writeln('		<td style="width: 32px"><img id="icon_' . $pid . '_b" alt="Vote Down" title="Vote Down" style="cursor: pointer;" src="/images/remove-32.png" onclick="vote(' . $pid . ', 0)"/></td>');
			} else if ($value > 0) {
				writeln('		<td style="width: 32px"><img id="icon_' . $pid . '_a" alt="You Voted Up" title="You Voted Up" style="cursor: pointer;" src="/images/up-32.png" onclick="vote(' . $pid . ', 1)"/></td>');
				writeln('		<td style="width: 32px"><img id="icon_' . $pid . '_b" alt="Undo Vote" title="Undo Vote" style="cursor: pointer;" src="/images/undo-32.png" onclick="vote(' . $pid . ', 0)"/></td>');
			}
		} else {
			if ($value < 0) {
				writeln('		<td style="width: 32px"><img alt="You Voted Down" title="You Voted Down" src="/images/down-32.png"/></td>');
				writeln('		<td style="width: 32px"><input type="image" name="undo" alt="Undo Vote" title="Undo Vote" src="/images/undo-32.png"/></td>');
			} else if ($value == 0) {
				writeln('		<td style="width: 32px"><input type="image" name="up" alt="Vote Up" title="Vote Up" src="/images/add-32.png"/></td>');
				writeln('		<td style="width: 32px"><input type="image" name="down" alt="Vote Down" title="Vote Down" src="/images/remove-32.png"/></td>');
			} else if ($value > 0) {
				writeln('		<td style="width: 32px"><img alt="You Voted Up" title="You Voted Up" src="/images/up-32.png"/></td>');
				writeln('		<td style="width: 32px"><input type="image" name="undo" alt="Undo Vote" title="Undo Vote" src="/images/undo-32.png"/></td>');
			}
		}
	}
	writeln('		<td style="width: 100%">');
	if ($javascript_enabled) {
		writeln('			<table class="fill" style="cursor: pointer;" onclick="toggle_body(' . $pid . ')">');
	} else {
		writeln('			<table class="fill">');
	}
	writeln('				<tr>');
	writeln('					<td id="score_' . $pid . '" style="width: 48px; text-align: center">' . $score . '</td>');
	writeln('					<td>');
	writeln('						<table class="fill">');
	writeln('							<tr>');
	writeln('								<td>' . $pipe["title"] . '</td>');
	writeln('							</tr>');
	writeln('							<tr>');
	writeln('								<td class="pipe_subtitle">by ' . $by . ' on ' . $date . ' (#' . $pipe["pid"] . ')</td>');
	writeln('							</tr>');
	writeln('						</table>');
	writeln('					</td>');
	writeln('				</tr>');
	writeln('			</table>');
	writeln('		</td>');
	writeln('		<td style="text-align: right; white-space: nowrap;"><a href="/pipe/' . $pid . '" class="icon_16" style="background-image: url(\'/images/chat-16.png\')"><b>' . $total . '</b> comments</a></td>');
	writeln('	</tr>');
	writeln('</table>');
	writeln('</div>');
	if ($javascript_enabled) {
		writeln('<div id="body_' . $pid . '" class="pipe_body" style="display: none">');
	} else {
		writeln('</form>');
		writeln('<div class="pipe_body">');
	}
	writeln($pipe["story"]);
	writeln('</div>');
}

