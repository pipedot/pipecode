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

function print_pipe($pipe_id)
{
	global $server_name;

	$pipe = db_get_rec("pipe", $pipe_id);
	$date = date("Y-m-d H:i", $pipe["time"]);
	$topic = db_get_rec("topic", $pipe["tid"]);
	$a["pipe_id"] = $pipe["pipe_id"];
	$a["body"] = $pipe["body"];
	$a["icon"] = $pipe["icon"];
	$a["short_id"] = $pipe["short_id"];
	$a["time"] = $pipe["time"];
	$a["title"] = $pipe["title"];
	$a["topic"] = $topic["topic"];
	$a["zid"] = $pipe["author_zid"];
	$a["comments"] = count_comments("pipe", $pipe_id);

	$row = sql("select sum(value) as score from pipe_vote where pipe_id = ?", $pipe_id);
	$a["score"] = (int) $row[0]["score"];
	if ($a["score"] > 0) {
		$a["score"] = "+" . $a["score"];
	}

	print_article($a);
}


function print_pipe_small($pipe_id, $full)
{
	global $server_name;
	global $auth_zid;
	global $auth_user;

	$pipe = db_get_rec("pipe", $pipe_id);
	$short_code = crypt_crockford_encode($pipe["short_id"]);
	$date = date("Y-m-d H:i", $pipe["time"]);
	$score = 0;
	$topic = db_get_rec("topic", $pipe["tid"]);
	$zid = $pipe["author_zid"];
	if ($zid == "") {
		$by = "<b>Anonymous Coward</b>";
	} else {
		$by = "<b>$zid</b>";
	}

	$comments = count_comments("pipe", $pipe_id);

	$row = sql("select value from pipe_vote where pipe_id = ? and zid = ?", $pipe_id, $auth_zid);
	if (count($row) == 0) {
		$value = 0;
	} else {
		$value = $row[0]["value"];
	}

	$row = sql("select sum(value) as score from pipe_vote where pipe_id = ?", $pipe_id);
	$score = (int) $row[0]["score"];
	if ($score > 0) {
		$score = "+$score";
	}

	if ($auth_user["javascript_enabled"]) {
		writeln('<div id="title_' . $pipe_id . '" class="pipe_title_collapse">');
	} else {
		beg_form("/pipe/$pipe_id/vote");
		writeln('<div id="title_' . $pipe_id . '" class="pipe_title_expand">');
	}
	writeln('<table class="fill">');
	writeln('	<tr>');
	if ($auth_zid != "") {
		if ($auth_user["javascript_enabled"]) {
			if ($value < 0) {
				writeln('		<td style="width: 32px"><div id="icon_' . $pipe_id . '_a" class="pipe_down" title="You Voted Down" onclick="vote(\'' . $pipe_id . '\', 1)"></div></td>');
				writeln('		<td style="width: 32px"><div id="icon_' . $pipe_id . '_b" class="pipe_undo" title="Undo Vote" onclick="vote(\'' . $pipe_id . '\', 0)"></div></td>');
			} else if ($value == 0) {
				writeln('		<td style="width: 32px"><div id="icon_' . $pipe_id . '_a" class="pipe_plus" title="Vote Up" onclick="vote(\'' . $pipe_id . '\', 1)"></div></td>');
				writeln('		<td style="width: 32px"><div id="icon_' . $pipe_id . '_b" class="pipe_minus" title="Vote Down" onclick="vote(\'' . $pipe_id . '\', 0)"></div></td>');
			} else if ($value > 0) {
				writeln('		<td style="width: 32px"><div id="icon_' . $pipe_id . '_a" class="pipe_up" title="You Voted Up" onclick="vote(\'' . $pipe_id . '\', 1)"></div></td>');
				writeln('		<td style="width: 32px"><div id="icon_' . $pipe_id . '_b" class="pipe_undo" title="Undo Vote" onclick="vote(\'' . $pipe_id . '\', 0)"></div></td>');
			}
		} else {
			if ($value < 0) {
				writeln('		<td style="width: 32px"><img alt="You Voted Down" title="You Voted Down" src="/images/down-64.png" style="width: 32px"/></td>');
				writeln('		<td style="width: 32px"><input type="image" name="undo" alt="Undo Vote" title="Undo Vote" src="/images/undo-64.png" style="width: 32px"/></td>');
			} else if ($value == 0) {
				writeln('		<td style="width: 32px"><input type="image" name="up" alt="Vote Up" title="Vote Up" src="/images/plus-64.png" style="width: 32px"/></td>');
				writeln('		<td style="width: 32px"><input type="image" name="down" alt="Vote Down" title="Vote Down" src="/images/minus-64.png" style="width: 32px"/></td>');
			} else if ($value > 0) {
				writeln('		<td style="width: 32px"><img alt="You Voted Up" title="You Voted Up" src="/images/up-64.png" style="width: 32px"/></td>');
				writeln('		<td style="width: 32px"><input type="image" name="undo" alt="Undo Vote" title="Undo Vote" src="/images/undo-64.png" style="width: 32px"/></td>');
			}
		}
	}
	writeln('		<td style="width: 100%">');
	if ($auth_user["javascript_enabled"]) {
		writeln('			<table class="pipe_pointer" onclick="toggle_body(\'' . $pipe_id . '\')">');
	} else {
		writeln('			<table class="fill">');
	}
	writeln('				<tr>');
	writeln('					<td id="score_' . $pipe_id . '" style="width: 48px; text-align: center">' . $score . '</td>');
	writeln('					<td>');
	writeln('						<table class="fill">');
	writeln('							<tr>');
	writeln('								<td>' . $pipe["title"] . '</td>');
	writeln('							</tr>');
	writeln('							<tr>');
	writeln('								<td class="pipe_subtitle">by ' . $by . ' on ' . $date . '</td>');
	writeln('							</tr>');
	writeln('						</table>');
	writeln('					</td>');
	writeln('				</tr>');
	writeln('			</table>');
	writeln('		</td>');
	writeln('		<td style="padding-right: 8px; text-align: right; white-space: nowrap;"><a href="/pipe/' . $short_code . '" class="icon_16 chat_16"><b>' . $comments["count"] . '</b> ' . $comments["label"] . '</a></td>');
	writeln('	</tr>');
	writeln('</table>');
	writeln('</div>');
	if ($auth_user["javascript_enabled"]) {
		writeln('<div id="body_' . $pipe_id . '" class="pipe_body" style="display: none">');
	} else {
		end_form();
		writeln('<div class="pipe_body">');
	}
	writeln($pipe["body"]);
	writeln('</div>');
}

