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

include("clean.php");
include("render.php");
include("captcha.php");
include("mail.php");


function send_notifications($parent, $comment)
{
	global $server_name;
	global $auth_zid;

	$new_subject = $comment["subject"];
	$new_cid = $comment["cid"];
	$new_zid = $comment["zid"];
	if ($new_zid == "") {
		$new_zid = "Anonymous Coward";
	}
	$parent = $comment["parent"];
	$sent_list = array();

	while ($parent > 0) {
		$comment = db_get_rec("comment", $parent);
		$zid = $comment["zid"];
		if ($zid != "" && $zid != $auth_zid && !in_array($zid, $sent_list)) {
			$a = article_info($comment);
			$subject = 'Reply to "' . $comment["subject"] . '" by ' . $new_zid;
			$body = "Your comment has a new reply.\n";
			$body .= "\n";
			$body .= "In the " . $a["type"] . ":\n";
			$body .= $a["title"] . "\n";
			$body .= $a["link"] . "\n";
			$body .= "\n";
			$body .= "Your original comment:\n";
			$body .= $comment["subject"] . "\n";
			$body .= "http://$server_name/comment/$parent\n";
			$body .= "\n";
			$body .= "The new reply:\n";
			$body .= "$new_subject\n";
			$body .= "http://$server_name/comment/$new_cid\n";
			$body .= "\n";

			send_web_mail($zid, $subject, $body, "", false);
			$sent_list[] = $zid;
		}

		$parent = $comment["parent"];
	}
}


function print_post_box($sid, $cid, $pid, $qid, $subject, $body, $coward)
{
	global $auth_zid;

	writeln('<form method="post">');
	if ($sid != 0) {
		writeln('<input type="hidden" name="sid" value="' . $sid . '"/>');
	}
	if ($cid != 0) {
		writeln('<input type="hidden" name="cid" value="' . $cid . '"/>');
	}
	if ($pid != 0) {
		writeln('<input type="hidden" name="pid" value="' . $pid . '"/>');
	}
	if ($qid != 0) {
		writeln('<input type="hidden" name="qid" value="' . $qid . '"/>');
	}
	writeln('<div class="dialog_title">Post Comment</div>');
	writeln('<div class="dialog_body">');
	writeln('<table class="fill">');
	writeln('	<tr>');
	writeln('		<td style="width: 80px">Subject</td>');
	writeln('		<td colspan="2"><input name="subject" type="text" value="' . $subject . '" required="required"/></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td style="width: 80px; vertical-align: top; padding-top: 12px">Comment</td>');
	writeln('		<td colspan="2"><textarea name="comment" style="height: 120px" required="required">' . $body . '</textarea></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	if ($auth_zid == "") {
		$question = captcha_challenge();
		writeln('		<td>Captcha</td>');
		writeln('		<td><table><tr><td>' . $question . '</td><td><input name="answer" type="text" style="margin-left: 8px; width: 100px"/></td></tr></table></td>');
	} else {
		writeln('		<td></td>');
		writeln('		<td><label><input name="coward" type="checkbox"' . ($coward ? ' checked="checked"' : '') . '/>Post Anonymously</label></td>');
	}
	writeln('		<td class="right"><input name="post" type="submit" value="Post"/> <input name="preview" type="submit" value="Preview"/></td>');
	writeln('	</tr>');
	writeln('</table>');
	writeln('</div>');
	writeln('</form>');
}


$can_moderate = false;

if (http_post()) {
	$sid = http_post_int("sid", array("required" => false));
	$cid = http_post_int("cid", array("required" => false));
	$pid = http_post_int("pid", array("required" => false));
	$qid = http_post_int("qid", array("required" => false));
	$subject = http_post_string("subject", array("len" => 200, "valid" => "[ALL]"));
	$body = http_post_string("comment", array("len" => 64000, "valid" => "[ALL]"));
	$answer = http_post_string("answer", array("required" => false));
	if ($auth_zid == "") {
		$zid = "";
		$coward = true;
		if (http_post("post")) {
			if (!captcha_verify($answer)) {
				die("captcha failed");
			}
		}
	} else {
		$coward = http_post_bool("coward");
		if ($coward) {
			$zid = "";
		} else {
			$zid = $auth_zid;
		}
	}

	$subject = clean_unicode($subject);
	$subject = clean_entities($subject);
	$new_body = str_replace("\n", "<br>", $body);
	$new_body = clean_html($new_body);
	$time = time();

	if ($sid == 0 && $cid == 0 && $pid == 0 && $qid == 0) {
		die("qid [$qid]");
		die("sid, cid, pid, and qid are empty");
	}
	if ($cid != 0) {
		$comment = db_get_rec("comment", $cid);
		$sid = $comment["sid"];
		$pid = $comment["pid"];
		$qid = $comment["qid"];
	}
	if ($sid != 0) {
		$story = db_get_rec("story", $sid);
		$day = date("Y-m-d", $story["time"]);
	}

	if (http_post("preview")) {
		$zid = $auth_zid;

		print_header("Post Comment");

		writeln('<table class="fill">');
		writeln('<tr>');
		writeln('<td class="left_col">');
		print_left_bar("main", "stories");
		writeln('</td>');
		writeln('<td class="fill">');

		writeln('<h1>Preview</h1>');
		writeln('<p>Check your links before you post!</p>');
		writeln('<div style="margin-bottom: 8px">');
		writeln(render_comment($subject, ($coward ? "" : $zid), $time, 0, $new_body));
		writeln('</div>');
		writeln('</div>');

		print_post_box($sid, $cid, $pid, $qid, $subject, $body, $coward);

		writeln('</td>');
		writeln('</tr>');
		writeln('</table>');
		print_footer();
		die();
	}

	$comment = array();
	$comment["cid"] = 0;
	$comment["sid"] = $sid;
	$comment["qid"] = $qid;
	$comment["pid"] = $pid;
	$comment["parent"] = $cid;
	$comment["zid"] = $zid;
	$comment["time"] = $time;
	$comment["subject"] = $subject;
	$comment["comment"] = $new_body;
	db_set_rec("comment", $comment);

	$comment = db_get_rec("comment", array("zid" => $zid, "time" => $time));
	send_notifications($cid, $comment);

	if ($sid != 0) {
		header("Location: /story/$day/" . $story["ctitle"]);
	} elseif ($pid != 0) {
		header("Location: /pipe/$pid");
	} elseif ($qid != 0) {
		header("Location: /poll/$qid");
	}
	die();
}

$sid = http_get_int("sid", array("required" => false));
$cid = http_get_int("cid", array("required" => false));
$pid = http_get_int("pid", array("required" => false));
$qid = http_get_int("qid", array("required" => false));

print_header("Post Comment");

writeln('<table class="fill">');
writeln('<tr>');
writeln('<td class="left_col">');
print_left_bar("main", "stories");
writeln('</td>');
writeln('<td class="fill">');

if ($cid != 0) {
	$comment = db_get_rec("comment", $cid);
	$subject = $comment["subject"];
	$zid = $comment["zid"];

	$re = false;
	if (strlen($subject) >= 4) {
		if (substr($subject, 0, 4) == "Re: ") {
			$re = true;
		}
	}
	if (!$re) {
		$subject = "Re: " . $comment["subject"];
	}

	writeln('<div style="margin-bottom: 8px">');
	writeln(render_comment($comment["subject"], $zid, $comment["time"], $comment["cid"], $comment["comment"]));
	writeln('</div>');
	writeln('</div>');

} else {
	$subject = "";
}

print_post_box($sid, $cid, $pid, $qid, $subject, "", false);

writeln('</td>');
writeln('</tr>');
writeln('</table>');

print_footer();
