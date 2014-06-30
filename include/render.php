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

function render_comment($subject, $zid, $time, $cid, $body, $last_seen = 0)
{
	global $server_name;
	global $can_moderate;
	global $auth_zid;
	global $protocol;

	$score = get_comment_score($cid);

	$s = "<article class=\"comment\">\n";
	if ($time > $last_seen) {
		$s .= "<h1>$subject (Score: $score)</h1>\n";
	} else {
		$s .= "<h2>$subject (Score: $score)</h2>\n";
	}
	$s .= "<h3>";
	if ($zid == "") {
		$s .= "by Anonymous Coward ";
	} else {
		$s .= "by <a href=\"" . user_page_link($zid) . "\">$zid</a> ";
	}
	$s .= "on " . date("Y-m-d H:i", $time) . " (<a href=\"$protocol://$server_name/comment/" . $cid . "\">#" . $cid . "</a>)</h3>\n";
	$s .= "<div>\n";
	$s .= "<div>";
	$s .= "$body\n";

	$reason = array("Normal", "Offtopic", "Flamebait", "Troll", "Redundant", "Insightful", "Interesting", "Informative", "Funny", "Overrated", "Underrated");
	if ($can_moderate && $zid != $auth_zid) {
		$row = run_sql("select rid from comment_vote where cid = ? and zid = ?", array($cid, $auth_zid));
		if (count($row) == 0) {
			$selected = 0;
		} else {
			$selected = $row[0]["rid"];
		}

		$s .= "<footer><a href=\"/post?cid=$cid\">Reply</a><select name=\"cid_$cid\">";
		for ($i = 0; $i < count($reason); $i++) {
			if ($i == $selected) {
				$s .= "<option value=\"$i\" selected=\"selected\">" . $reason[$i] . "</option>";
			} else {
				$s .= "<option value=\"$i\">" . $reason[$i] . "</option>";
			}
		}
		$s .= "</select> <input type=\"submit\" value=\"Moderate\"/></footer>\n";
	} else {
		$s .= "<footer><a href=\"$protocol://$server_name/post?cid=$cid\">Reply</a></footer>\n";
	}
	$s .= "</div>\n";

	return $s;
}


function render_comment_json($subject, $zid, $time, $cid, $body)
{
	global $can_moderate;
	global $auth_zid;

	$score = get_comment_score($cid);
	$rid = -1;
	if ($can_moderate) {
		$row = run_sql("select rid from comment_vote where cid = ? and zid = ?", array($cid, $auth_zid));
		if (count($row) == 0) {
			$rid = 0;
		} else {
			$rid = $row[0]["rid"];
		}
	}

	$s = "\$level{\n";
	$s .= "\$level	\"cid\": $cid,\n";
	$s .= "\$level	\"zid\": \"$zid\",\n";
	$s .= "\$level	\"time\": $time,\n";
	$s .= "\$level	\"score\": \"$score\",\n";
	$s .= "\$level	\"rid\": $rid,\n";
	$s .= "\$level	\"subject\": \"" . addcslashes($subject, "\\\"") . "\",\n";
	$s .= "\$level	\"comment\": \"" . addcslashes($body, "\\\"") . "\",\n";
	$s .= "\$level	\"reply\": [\n";

	return $s;
}


function recursive_render($render, $parent, $keys, $cid)
{
	$s = $render[$cid];

	for ($i = 0; $i < count($keys); $i++) {
		$child = $keys[$i];
		if ($parent[$child] == $cid) {
			$s .= recursive_render($render, $parent, $keys, $child);
		}
	}
	$s .= "</div>\n";
	$s .= "</article>\n";

	return $s;
}


function recursive_render_json($render, $parent, $keys, $cid, $level)
{
	$s = str_replace("\$level", str_repeat("		", $level), $render[$cid]);

	$count = 0;
	for ($i = 0; $i < count($keys); $i++) {
		$child = $keys[$i];
		if ($parent[$child] == $cid) {
			$s .= recursive_render_json($render, $parent, $keys, $child, $level + 1) . "\n";
			$count++;
		}
	}
	if ($count > 0) {
		$s = substr($s, 0, -2) . "\n";
	}
	$s .= str_repeat("		", $level) . "	]\n";
	$s .= str_repeat("		", $level) . "},";

	return $s;
}


function render_page($sid, $pid, $qid, $json)
{
	global $auth_zid;
	global $can_moderate;
	global $hide_value;
	global $expand_value;

	$render = array();
	$username = array();
	$parent = array();

	if ($sid != 0) {
		$comments = db_get_list("comment", "time", array("sid" => $sid));
		if ($auth_zid == "") {
			$last_seen = 0;
		} else {
			if (db_has_rec("story_view", array("sid" => $sid, "zid" => $auth_zid))) {
				$view = db_get_rec("story_view", array("sid" => $sid, "zid" => $auth_zid));
				$view["last_time"] = $view["time"];
				$last_seen = $view["time"];
			} else {
				$view = array();
				$view["sid"] = $sid;
				$view["zid"] = $auth_zid;
				$view["last_time"] = 0;
				$last_seen = 0;
			}
			$view["time"] = time();
			db_set_rec("story_view", $view);
		}
	} elseif ($pid != 0) {
		$comments = db_get_list("comment", "time", array("pid" => $pid));
		if ($auth_zid == "") {
			$last_seen = 0;
		} else {
			if (db_has_rec("pipe_view", array("pid" => $pid, "zid" => $auth_zid))) {
				$view = db_get_rec("pipe_view", array("pid" => $pid, "zid" => $auth_zid));
				$view["last_time"] = $view["time"];
				$last_seen = $view["time"];
			} else {
				$view = array();
				$view["pid"] = $pid;
				$view["zid"] = $auth_zid;
				$view["last_time"] = 0;
				$last_seen = 0;
			}
			$view["time"] = time();
			db_set_rec("pipe_view", $view);
		}
	} elseif ($qid != 0) {
		$comments = db_get_list("comment", "time", array("qid" => $qid));
		if ($auth_zid == "") {
			$last_seen = 0;
		} else {
			if (db_has_rec("poll_view", array("qid" => $qid, "zid" => $auth_zid))) {
				$view = db_get_rec("poll_view", array("qid" => $qid, "zid" => $auth_zid));
				$view["last_time"] = $view["time"];
				$last_seen = $view["time"];
			} else {
				$view = array();
				$view["qid"] = $qid;
				$view["zid"] = $auth_zid;
				$view["last_time"] = 0;
				$last_seen = 0;
			}
			$view["time"] = time();
			db_set_rec("poll_view", $view);
		}
	}
	$total = count($comments);
	$k = array_keys($comments);

	if ($json) {
		writeln('{');
		writeln('	"reply": [');
	} else {
		if ($can_moderate) {
			beg_form("/threshold");
		}
		writeln('<div class="comment_header">');
		writeln('	<table class="fill">');
		writeln('		<tr>');
		if ($sid != 0) {
			writeln('			<td style="width: 30%"><a href="/post?sid=' . $sid . '" class="icon_16" style="background-image: url(\'/images/chat-16.png\')">Reply</a></td>');
		} elseif ($pid != 0) {
			writeln('			<td style="width: 30%"><a href="/post?pid=' . $pid . '" class="icon_16" style="background-image: url(\'/images/chat-16.png\')">Reply</a></td>');
		} elseif ($qid != 0) {
			writeln('			<td style="width: 30%"><a href="/post?qid=' . $qid . '" class="icon_16" style="background-image: url(\'/images/chat-16.png\')">Reply</a></td>');
		}
		if ($can_moderate && false) {
			writeln('			<td style="width: 30%">');
			writeln('				<table>');
			writeln('					<tr>');
			writeln('						<td>Hide</td>');
			$s = "<select name=\"hide_value\">";
			for ($i = -1; $i <= 5; $i++) {
				if ($i == $hide_value) {
					$s .= "<option selected=\"selected\">$i</option>";
				} else {
					$s .= "<option>$i</option>";
				}
			}
			$s .= "</select>";
			writeln('						<td>' . $s . '</td>');
			writeln('						<td><input type="submit" value="Change"/></td>');
			writeln('					</tr>');
			writeln('				</table>');
			writeln('			</td>');
			writeln('			<td style="width: 30%">');
			writeln('				<table>');
			writeln('					<tr>');
			writeln('						<td>Expand</td>');
			$s = "<select name=\"expand_value\">";
			for ($i = -1; $i <= 5; $i++) {
				if ($i == $expand_value) {
					$s .= "<option selected=\"selected\">$i</option>";
				} else {
					$s .= "<option>$i</option>";
				}
			}
			$s .= "</select>";
			writeln('						<td>' . $s . '</td>');
			writeln('						<td><input type="submit" value="Change"/></td>');
			writeln('					</tr>');
			writeln('				</table>');
			writeln('			</td>');
		}
		writeln('			<td style="text-align: right; width: 30%">' . $total . ' comments</td>');
		writeln('		</tr>');
		writeln('	</table>');
		writeln('</div>');
		if ($can_moderate) {
			end_form();
		}
	}

	for ($i = 0; $i < $total; $i++) {
		$comment = $comments[$k[$i]];
		$zid = $comment["zid"];
		if ($json) {
			$render[$comment["cid"]] = render_comment_json($comment["subject"], $zid, $comment["time"], $comment["cid"], $comment["comment"]);
		} else {
			$render[$comment["cid"]] = render_comment($comment["subject"], $zid, $comment["time"], $comment["cid"], $comment["comment"], $last_seen);
		}
		$parent[$comment["cid"]] = $comment["parent"];
	}

	$keys = array_keys($render);
	$s = "";

	if (!$json && $can_moderate) {
		beg_form("/moderate_noscript");
		if ($sid != 0) {
			writeln('<input type="hidden" name="sid" value="' . $sid . '"/>');
		} elseif ($pid != 0) {
			writeln('<input type="hidden" name="pid" value="' . $pid . '"/>');
		} elseif ($qid != 0) {
			writeln('<input type="hidden" name="qid" value="' . $qid . '"/>');
		}
	}
	for ($i = 0; $i < $total; $i++) {
		$comment = $comments[$keys[$i]];

		if ($comment["parent"] == 0) {
			if ($json) {
				$s .= recursive_render_json($render, $parent, $keys, $comment["cid"], 1) . "\n";
			} else {
				writeln(recursive_render($render, $parent, $keys, $comment["cid"]));
			}
		}
	}

	if ($json) {
		if ($total > 0) {
			writeln(substr($s, 0, -2));
		}
		writeln('	]');
		writeln('}');
	} else {
		if ($can_moderate) {
			end_form();
		}
	}
}


function render_sliders($sid, $pid, $qid)
{
	global $hide_value;
	global $expand_value;

	if ($sid != 0) {
		$row = run_sql("select count(cid) as comments from comment where sid = ?", array($sid));
	} elseif ($pid != 0) {
		$row = run_sql("select count(cid) as comments from comment where pid = ?", array($pid));
	} elseif ($qid != 0) {
		$row = run_sql("select count(cid) as comments from comment where qid = ?", array($qid));
	}
	$total = (int) $row[0]["comments"];

	writeln('<div class="comment_header">');
	writeln('	<table class="fill">');
	writeln('		<tr>');
	if ($sid != 0) {
		writeln('			<td style="width: 20%"><a href="/post?sid=' . $sid . '" class="icon_16" style="background-image: url(\'/images/chat-16.png\')">Reply</a></td>');
	} elseif ($pid != 0) {
		writeln('			<td style="width: 20%"><a href="/post?pid=' . $pid . '" class="icon_16" style="background-image: url(\'/images/chat-16.png\')">Reply</a></td>');
	} elseif ($qid != 0) {
		writeln('			<td style="width: 20%"><a href="/post?qid=' . $qid . '" class="icon_16" style="background-image: url(\'/images/chat-16.png\')">Reply</a></td>');
	}
	writeln('			<td style="width: 30%">');
	writeln('				<table>');
	writeln('					<tr>');
	writeln('						<td>Hide</td>');
	writeln('						<td><input id="slider_hide" name="slider_hide" type="range" value="' . $hide_value . '" min="-1" max="5" onchange="update_hide_slider()"/></td>');
	writeln('						<td id="label_hide">' . $hide_value . '</td>');
	writeln('					</tr>');
	writeln('				</table>');
	writeln('			</td>');
	writeln('			<td style="width: 30%">');
	writeln('				<table>');
	writeln('					<tr>');
	writeln('						<td>Expand</td>');
	writeln('						<td><input id="slider_expand" name="slider_expand" type="range" value="' . $expand_value . '" min="-1" max="5" onchange="update_expand_slider()"/></td>');
	writeln('						<td id="label_expand">' . $expand_value . '</td>');
	writeln('					</tr>');
	writeln('				</table>');
	writeln('			</td>');
	writeln('			<td style="width: 20%; text-align: right">' . $total . ' comments</td>');
	writeln('		</tr>');
	writeln('	</table>');
	writeln('</div>');

	writeln('<div id="comment_box"></div>');
}


function get_comment_score($cid) {
	global $cache_enabled;

//	if ($cache_enabled) {
//		$cache_key = "comment_score.$cid";
//		$s = cache_get($cache_key);
//		if ($s !== false) {
//			return $s;
//		}
//	}

	$row = run_sql("select sum(value) as score from comment_vote inner join reason on comment_vote.rid = reason.rid where cid = ?", array($cid));
	$score = (int) $row[0]["score"];

	if (db_has_rec("comment", $cid)) {
		$comment = db_get_rec("comment", $cid);
		if ($comment["zid"] != "") {
			$score++;
		}
	}

	if ($score < -1) {
		$score = -1;
	} else if ($score > 5) {
		$score = 5;
	}

	//$up = array("Insightful", "Interesting", "Informative", "Funny", "Underrated");
	//$down = array("Offtopic", "Flamebait", "Troll", "Redundant", "Overrated");
	$reason = "";
	$row = run_sql("select reason, count(reason) as reason_count, value from comment_vote inner join reason on comment_vote.rid = reason.rid where cid = ? group by reason order by value desc, reason_count desc", array($cid));
	for ($i = 0; $i < count($row); $i++) {
		if ($score < 0 && $row[$i]["value"] < 0 && $row[$i]["reason_count"] > 1 && $row[$i]["reason"] != "Overrated") {
			$reason = ", " . $row[$i]["reason"];
			break;
		}
		if ($score > 1 && $row[$i]["value"] > 0 && $row[$i]["reason_count"] > 1 && $row[$i]["reason"] != "Underrated") {
			$reason = ", " . $row[$i]["reason"];
			break;
		}
	}

//	if ($cache_enabled) {
//		cache_set($cache_key, "$score$reason");
//	}

	return "$score$reason";
}
