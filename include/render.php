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

function render_comment($subject, $zid, $time, $comment_id, $body, $last_seen = 0, $short_id, $article_link = "", $article_title = "")
{
	global $server_name;
	global $can_moderate;
	global $auth_zid;
	global $protocol;
	global $reasons;

	list($score, $reason) = get_comment_score($comment_id);
	$score_reason = $score;
	if ($reason != "") {
		$score_reason .= ", $reason";
	}

	$s = "<article class=\"comment\">\n";
	if ($time > $last_seen) {
		$s .= "<h1>$subject (Score: $score_reason)</h1>\n";
	} else {
		$s .= "<h2>$subject (Score: $score_reason)</h2>\n";
	}
	$date = date("Y-m-d H:i", $time);
	//if ($comment_id != "") {
	//	$date = "<a href=\"$protocol://$server_name/comment/$short_code\">$date</a>";
	//}
	if ($article_link != "") {
		//print "new link [$article_link] new title [$article_title]";
		$in = " in <a href=\"$article_link\"><b>$article_title</b></a>";
	} else {
		$in = "";
	}
	$short_code = crypt_crockford_encode($short_id);
	$s .= "<h3>by " . user_page_link($zid, true) . "$in on $date (<a href=\"$protocol://$server_name/$short_code\">#$short_code</a>)</h3>\n";
	$s .= "<div class=\"comment_outline\">\n";
	$s .= "<div>";
	$s .= "<div class=\"comment_body\">$body</div>\n";

	//$reason = array("Normal", "Offtopic", "Flamebait", "Troll", "Redundant", "Insightful", "Interesting", "Informative", "Funny", "Overrated", "Underrated");
	if ($can_moderate && $auth_zid != "" && $zid != $auth_zid) {
		$row = sql("select reason from comment_vote where comment_id = ? and zid = ?", $comment_id, $auth_zid);
		if (count($row) == 0) {
			$selected = "Normal";
		} else {
			$selected = $row[0]["reason"];
		}

		$s .= "<footer><a href=\"$protocol://$server_name/post?comment_id=$comment_id\">Reply</a><select name=\"comment_" . $comment_id . "\">";
		$k = array_keys($reasons);
		for ($i = 0; $i < count($reasons); $i++) {
			if ($k[$i] == $selected) {
				//$s .= "<option value=\"$i\" selected=\"selected\">" . $reason[$i] . "</option>";
				$s .= "<option selected=\"selected\">" . $k[$i] . "</option>";
			} else {
				//$s .= "<option value=\"$i\">" . $reason[$i] . "</option>";
				$s .= "<option>" . $k[$i] . "</option>";
			}
		}
		$s .= "</select> <input type=\"submit\" value=\"Moderate\"/></footer>\n";
	} else {
		$s .= "<footer>\n";
		$s .= "	<div><a href=\"$protocol://$server_name/post?comment_id=$comment_id\">Reply</a></div>\n";
		$s .= "	<div class=\"right\">";
		if ($auth_zid !== "" && $auth_zid === $zid) {
			$s .= "		<a class=\"icon_16 notepad_16\" style=\"xcolor: #666666\" href=\"$protocol://$server_name/comment/$short_code/edit\">Edit</a>";
		}
		$s .= "	</div>\n";
		$s .= "</footer>\n";
	}
	$s .= "</div>\n";

	return $s;
}


function render_comment_json($subject, $zid, $time, $comment_id, $body, $short_id)
{
	global $can_moderate;
	global $auth_zid;

	list($score, $reason) = get_comment_score($comment_id);
	$rid = -1;
	if ($can_moderate) {
		$row = sql("select reason from comment_vote where comment_id = ? and zid = ?", $comment_id, $auth_zid);
		if (count($row) == 0) {
			$vote = "";
		} else {
			$vote = $row[0]["reason"];
		}
	}

	$s = "\$level{\n";
	$s .= "\$level	\"comment_id\": \"$comment_id\",\n";
	$s .= "\$level	\"body\": \"" . addcslashes($body, "\\\"") . "\",\n";
	$s .= "\$level	\"score\": $score,\n";
	$s .= "\$level	\"reason\": \"$reason\",\n";
	$s .= "\$level	\"short\": \"" . crypt_crockford_encode($short_id) . "\",\n";
	$s .= "\$level	\"subject\": \"" . addcslashes($subject, "\\\"") . "\",\n";
	$s .= "\$level	\"time\": $time,\n";
	$s .= "\$level	\"vote\": \"$vote\",\n";
	$s .= "\$level	\"zid\": \"$zid\",\n";
	$s .= "\$level	\"reply\": [\n";

	return $s;
}


function recursive_render($render, $parent, $keys, $comment_id)
{
	$s = $render[$comment_id];

	for ($i = 0; $i < count($keys); $i++) {
		$child = $keys[$i];
		if ($parent[$child] == $comment_id) {
			$s .= recursive_render($render, $parent, $keys, $child);
		}
	}
	$s .= "</div>\n";
	$s .= "</article>\n";

	return $s;
}


function recursive_render_json($render, $parent, $keys, $comment_id, $level)
{
	$s = str_replace("\$level", str_repeat("		", $level), $render[$comment_id]);

	$count = 0;
	for ($i = 0; $i < count($keys); $i++) {
		$child = $keys[$i];
		if ($parent[$child] == $comment_id) {
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


function render_page($type, $root_id, $json)
{
	global $protocol;
	global $server_name;
	global $auth_zid;
	global $can_moderate;
	global $hide_value;
	global $expand_value;

	$render = array();
	$username = array();
	$parent = array();

	if ($auth_zid == "") {
		$last_seen = 0;
	} else {
		if (db_has_rec("{$type}_view", array("{$type}_id" => $root_id, "zid" => $auth_zid))) {
			$view = db_get_rec("{$type}_view", array("{$type}_id" => $root_id, "zid" => $auth_zid));
			$view["last_time"] = $view["time"];
			$last_seen = $view["time"];
		} else {
			$view = array();
			$view["{$type}_id"] = $root_id;
			$view["zid"] = $auth_zid;
			$view["last_time"] = 0;
			$last_seen = 0;
		}
		$view["time"] = time();
		db_set_rec("{$type}_view", $view);
	}

	$comments = db_get_list("comment", "publish_time", array("root_id" => $root_id));
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
		writeln('			<td style="width: 30%"><a rel="nofollow" href="' . $protocol . '://' . $server_name . '/post?type=' . $type . '&amp;root_id=' . $root_id . '" class="icon_16 chat_16">Reply</a></td>');
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
			$render[$comment["comment_id"]] = render_comment_json($comment["subject"], $zid, $comment["edit_time"], $comment["comment_id"], $comment["body"], $comment["short_id"]);
		} else {
			$render[$comment["comment_id"]] = render_comment($comment["subject"], $zid, $comment["edit_time"], $comment["comment_id"], $comment["body"], $last_seen, $comment["short_id"]);
		}
		$parent[$comment["comment_id"]] = $comment["parent_id"];
	}

	$keys = array_keys($render);
	$s = "";

	if (!$json && $can_moderate) {
		beg_form("$protocol://$server_name/moderate_noscript");
		writeln('<input type="hidden" name="type" value="' . $type . '"/>');
		writeln('<input type="hidden" name="root_id" value="' . $root_id . '"/>');
	}
	for ($i = 0; $i < $total; $i++) {
		$comment = $comments[$keys[$i]];

		if ($comment["parent_id"] == "") {
			if ($json) {
				$s .= recursive_render_json($render, $parent, $keys, $comment["comment_id"], 1) . "\n";
			} else {
				writeln(recursive_render($render, $parent, $keys, $comment["comment_id"]));
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


function render_sliders($type, $root_id)
{
	global $protocol;
	global $server_name;
	global $hide_value;
	global $expand_value;

	$row = sql("select count(*) as comments from comment where root_id = ?", $root_id);
	$total = (int) $row[0]["comments"];

	writeln('<div class="comment_header">');
	writeln('	<table class="fill">');
	writeln('		<tr>');
	writeln('			<td style="width: 20%"><a href="' . $protocol . '://' . $server_name . '/post?type=' . $type . '&amp;root_id=' . $root_id . '" class="icon_16 chat_16">Reply</a></td>');
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


function get_comment_score($comment_id)
{
	global $cache_enabled;
	global $auth_zid;

//	if ($cache_enabled) {
//		$cache_key = "comment_score.$comment_id";
//		$s = cache_get($cache_key);
//		if ($s !== false) {
//			return $s;
//		}
//	}

	if ($comment_id == "") {
		return array(($auth_zid == "" ? 0 : 1), "");
	}

	//$row = sql("select sum(value) as score from comment_vote inner join reason on comment_vote.rid = reason.rid where comment_id = ?", $comment_id);
	$row = sql("select sum(value) as score from comment_vote where comment_id = ?", $comment_id);
	$score = (int) $row[0]["score"];

	//if (db_has_rec("comment", $comment_id)) {
		$comment = db_get_rec("comment", $comment_id);
		if ($comment["zid"] != "") {
			$score++;
		}
	//}

	if ($score < -1) {
		$score = -1;
	} else if ($score > 5) {
		$score = 5;
	}

	//$up = array("Insightful", "Interesting", "Informative", "Funny", "Underrated");
	//$down = array("Offtopic", "Flamebait", "Troll", "Redundant", "Overrated");
	$reason = "";
	//$row = sql("select reason, count(reason) as reason_count, value from comment_vote inner join reason on comment_vote.rid = reason.rid where comment_id = ? group by reason order by value desc, reason_count desc", $comment_id);
	//$row = sql("select reason, count(reason) as reason_count, value from comment_vote where comment_id = ? group by reason order by value desc, reason_count desc", $comment_id);
	$row = sql("select reason, count(reason) as reason_count, value from comment_vote where comment_id = ? group by reason order by reason_count desc", $comment_id);
	for ($i = 0; $i < count($row); $i++) {
		//if ($score < 0 && $row[$i]["value"] < 0 && $row[$i]["reason_count"] > 1 && $row[$i]["reason"] != "Overrated") {
		if ($score < 0 && $row[$i]["value"] < 0 && $row[$i]["reason"] != "Overrated") {
			$reason = $row[$i]["reason"];
			break;
		}
		//if ($score > 1 && $row[$i]["value"] > 0 && $row[$i]["reason_count"] > 1 && $row[$i]["reason"] != "Underrated") {
		if ($score > 0 && $row[$i]["value"] > 0 && $row[$i]["reason"] != "Underrated") {
			$reason = $row[$i]["reason"];
			break;
		}
	}

//	if ($cache_enabled) {
//		cache_set($cache_key, "$score$reason");
//	}

	return array($score, $reason);
}
