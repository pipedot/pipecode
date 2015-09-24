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

function print_comment($comment, $article = false, $article_type_id = 0, $last_seen = 0)
{
	if (($article_type_id == 0) && ((is_bool($article) && $article) || is_array($artcile))) {
		$short = db_get_rec("short", $comment["article_id"]);
		$article_type_id = $short["type_id"];
	}
	if (is_bool($article) && $article) {
		$article_type = item_type($article_type_id);
		$article = db_get_rec($article_type, $comment["article_id"]);
	}
	if (is_array($article)) {
		$article_link = item_link($article_type_id, $comment["article_id"], $article);
		if ($article_type_id == TYPE_POLL) {
			$article_title = $article["question"];
		} else if ($article_type_id == TYPE_CARD) {
			$article_title = "#" . crypt_crockford_encode($article["card_id"]);
		} else {
			$article_title = $article["title"];
		}
	} else {
		$article_link = "";
		$article_title = "";
	}

	print render_comment($comment["subject"], $comment["zid"], $comment["edit_time"], $comment["comment_id"], $comment["body"], $last_seen, $article_link, $article_title, $comment["junk_status"]);
	writeln('</div>');
	writeln('</article>');
}


function render_comment($subject, $zid, $time, $comment_id, $body, $last_seen = 0, $article_link = "", $article_title = "", $junk_status = 0)
{
	global $server_name;
	global $can_moderate;
	global $junk;
	global $query;
	global $auth_user;
	global $auth_zid;
	global $protocol;
	global $reasons;

	list($score, $reason) = get_comment_score($comment_id);
	$score_reason = $score;
	if ($reason != "") {
		$score_reason .= ", $reason";
	}

	$comment_code = crypt_crockford_encode($comment_id);

	$body = make_clickable($body);

	$s = "<article class=\"comment\">\n";
	if ($junk_status > 0) {
		$color = "junk";
	} else if ($time > $last_seen) {
		$color = "new";
	} else {
		$color = "old";
	}
	$s .= "<h3 class=\"color-$color\">$subject (Score: <span id=\"score_$comment_code\">$score_reason</span>)</h3>\n";
	$date = date("Y-m-d H:i", $time);

	if ($article_link != "") {
		$in = " in <a href=\"$article_link\"><b>$article_title</b></a>";
	} else {
		$in = "";
	}
	if ($comment_id == 0) {
		$code = "";
	} else {
		$code = " (<a href=\"$protocol://$server_name/$comment_code\">#$comment_code</a>)";
	}
	$s .= "<h4>by " . user_link($zid, ["tag" => true]) . "$in on $date$code</h4>\n";
	$s .= "<div class=\"comment-outline\">\n";
	$s .= "<div>";
	$s .= "<div class=\"comment-body\">$body</div>\n";

	if ($can_moderate && $auth_zid != "" && $zid != $auth_zid) {
		$row = sql("select reason from comment_vote where comment_id = ? and zid = ?", $comment_id, $auth_zid);
		if (count($row) == 0) {
			$selected = "Normal";
		} else {
			$selected = $row[0]["reason"];
		}

		$s .= "<footer><a href=\"$protocol://$server_name/post/$comment_code\">Reply</a>";
		if ($auth_user["javascript_enabled"]) {
			$s .= "<select name=\"comment_$comment_code\" onchange=\"moderate(this, '$comment_code')\">";
		} else {
			$s .= "<select name=\"comment_$comment_code\">";
		}
		$k = array_keys($reasons);
		for ($i = 0; $i < count($reasons); $i++) {
			if ($k[$i] == $selected) {
				$s .= "<option selected>" . $k[$i] . "</option>";
			} else {
				$s .= "<option>" . $k[$i] . "</option>";
			}
		}
		$s .= "</select>";
		if (!$auth_user["javascript_enabled"]) {
			$s .= " <input type=\"submit\" value=\"Moderate\"></footer>\n";
		}
	} else if ($junk) {
		if ($query == "default=spam") {
			$junk_default = true;
		} else {
			$junk_default = false;
		}
		$s .= "<footer>\n";
		$s .= "<div>\n";
		if ($junk_default) {
			$s .= "	<label><input name=\"junk_$comment_code\" type=\"radio\" value=\"spam\" checked>Spam</label>\n";
			$s .= "	<label><input name=\"junk_$comment_code\" type=\"radio\" value=\"not-junk\">Not Junk</label>\n";
		} else {
			$s .= "	<label><input name=\"junk_$comment_code\" type=\"radio\" value=\"spam\">Spam</label>\n";
			$s .= "	<label><input name=\"junk_$comment_code\" type=\"radio\" value=\"not-junk\" checked>Not Junk</label>\n";
		}
		$s .= "</div>\n";
		$s .= "<div class=\"right\">\n";
		if ($junk_default) {
			$s .= "	<label><input name=\"ban_$comment_code\" type=\"checkbox\" checked>Ban IP</label>\n";
		} else {
			$s .= "	<label><input name=\"ban_$comment_code\" type=\"checkbox\">Ban IP</label>\n";
		}
		$s .= "</div>\n";
		//$s .= "	<label><input type=\"radio\">Spam</label>\n";
		//$s .= "	<label><input type=\"radio\">Abuse</label>\n";
		//$s .= "	<label><input type=\"radio\">Inappropriate</label>\n";
		$s .= "</footer>\n";
	} else {
		$s .= "<footer>\n";
		$s .= "	<div><a href=\"$protocol://$server_name/post/$comment_code\">Reply</a></div>\n";
		//$s .= "	<div class=\"right\">\n";
		//$s .= "	<div>\n";
		if ($auth_zid !== "" && $auth_zid === $zid && $comment_id > 0) {
			$s .= "	<div><a class=\"icon-16 notepad-16\" href=\"$protocol://$server_name/comment/$comment_code/edit\">Edit</a></div>\n";
		}
		//$s .= "	</div>\n";
		$s .= "</footer>\n";
	}
	$s .= "</div>\n";

	return $s;
}


function render_comment_json($subject, $zid, $time, $comment_id, $body, $junk_status)
{
	global $can_moderate;
	global $auth_zid;
	global $auth_user;

	list($score, $reason) = get_comment_score($comment_id);
	$rid = -1;
	if ($can_moderate) {
		$row = sql("select reason from comment_vote where comment_id = ? and zid = ?", $comment_id, $auth_zid);
		if (count($row) == 0) {
			$vote = "";
		} else {
			$vote = $row[0]["reason"];
		}
	} else {
		$vote = "";
	}

	$body = make_clickable($body);

	$s = "\$level{\n";
	$s .= "\$level	\"code\": \"" . crypt_crockford_encode($comment_id) . "\",\n";
	$s .= "\$level	\"body\": \"" . addcslashes($body, "\\\"") . "\",\n";
	$s .= "\$level	\"score\": $score,\n";
	$s .= "\$level	\"reason\": \"$reason\",\n";
	$s .= "\$level	\"junk\": $junk_status,\n";
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


function render_page($type_id, $article_id, $json)
{
	global $protocol;
	global $server_name;
	global $auth_zid;
	global $auth_user;
	global $can_moderate;
	global $hide_value;
	global $expand_value;

	$render = [];
	$parent = [];
	$comments = [];

	$article_code = crypt_crockford_encode($article_id);

	if ($auth_zid === "") {
		$last_seen = 0;
	} else {
		$last_seen = update_view_time($article_id);
	}

	if ($auth_user["show_junk_enabled"]) {
		$row = sql("select * from comment where article_id = ? order by publish_time", $article_id);
	} else {
		$row = sql("select * from comment where article_id = ? and junk_status <= 0 order by publish_time", $article_id);
	}
	$total = count($row);

	if ($json) {
		writeln('{');
		writeln('	"reply": [');
	} else {
		if ($can_moderate) {
			beg_form("/threshold");
		}
		writeln('<div class="comment-header">');
		writeln('	<table class="fill">');
		writeln('		<tr>');
		writeln('			<td style="width: 30%"><a rel="nofollow" href="' . $protocol . '://' . $server_name . '/post/' . $article_code . '" class="icon-16 chat-16">Reply</a></td>');
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
			writeln('						<td><input type="submit" value="Change"></td>');
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
			writeln('						<td><input type="submit" value="Change"></td>');
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
		//$comment = $comments[$k[$i]];
		$comment = $row[$i];
		$comments[$comment["comment_id"]] = $row[$i];
		//$zid = $comment["zid"];
		if ($json) {
			$render[$comment["comment_id"]] = render_comment_json($comment["subject"], $comment["zid"], $comment["edit_time"], $comment["comment_id"], $comment["body"], $comment["junk_status"]);
		} else {
			$render[$comment["comment_id"]] = render_comment($comment["subject"], $comment["zid"], $comment["edit_time"], $comment["comment_id"], $comment["body"], $last_seen, "", "", $comment["junk_status"]);
		}
		$parent[$comment["comment_id"]] = $comment["parent_id"];
	}

	$keys = array_keys($render);
	$s = "";

	if (!$json && $can_moderate) {
		beg_form("$protocol://$server_name/moderate_noscript");
		writeln('<input type="hidden" name="article_code" value="' . $article_code . '">');
	}
	for ($i = 0; $i < $total; $i++) {
		$comment = $comments[$keys[$i]];

		if ($comment["parent_id"] == "0") {
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


function print_sliders($type_id, $article_id)
{
	global $protocol;
	global $server_name;
	global $hide_value;
	global $expand_value;

	$type = item_type($type_id);
	$comments = count_comments($type_id, $article_id);
	$rec = db_get_rec($type, $article_id);
	$article_code = crypt_crockford_encode($article_id);

	writeln('<div class="comment-header">');
	writeln('	<table class="fill">');
	writeln('		<tr>');
	writeln('			<td style="width: 20%"><a href="' . $protocol . '://' . $server_name . '/post/' . $article_code . '" class="icon-16 chat-16">Reply</a></td>');
	writeln('			<td style="width: 30%">');
	writeln('				<table>');
	writeln('					<tr>');
	writeln('						<td>Hide</td>');
	writeln('						<td><input id="slider_hide" name="slider_hide" type="range" value="' . $hide_value . '" min="-1" max="5" onchange="update_hide_slider()"></td>');
	writeln('						<td id="label_hide">' . $hide_value . '</td>');
	writeln('					</tr>');
	writeln('				</table>');
	writeln('			</td>');
	writeln('			<td style="width: 30%">');
	writeln('				<table>');
	writeln('					<tr>');
	writeln('						<td>Expand</td>');
	writeln('						<td><input id="slider_expand" name="slider_expand" type="range" value="' . $expand_value . '" min="-1" max="5" onchange="update_expand_slider()"></td>');
	writeln('						<td id="label_expand">' . $expand_value . '</td>');
	writeln('					</tr>');
	writeln('				</table>');
	writeln('			</td>');
	writeln('			<td style="width: 20%; text-align: right">' . $comments["tag"] . '</td>');
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
