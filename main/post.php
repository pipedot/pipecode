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


function print_post_box($sid, $cid, $pid, $qid, $subject, $dirty_body, $coward)
{
	global $auth_zid;
	global $auth_user;

	beg_form();
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
	writeln('		<td colspan="2" style="padding-bottom: 4px"><input name="subject" type="text" value="' . $subject . '" required="required"/></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td style="width: 80px; vertical-align: top; padding-top: 12px">Comment</td>');
	writeln('		<td colspan="2" style="padding-bottom: 4px"><textarea name="comment" style="height: 120px" required="required">' . $dirty_body . '</textarea></td>');
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
	end_form();
	if ($auth_user["javascript_enabled"] && $auth_user["wysiwyg_enabled"]) {
		writeln('<script type="text/javascript" src="/lib/ckeditor/ckeditor.js"></script>');
		writeln('<script type="text/javascript">');
		writeln();
		writeln('CKEDITOR.replace("comment",');
		writeln('{');
		writeln('	resize_enabled: false,');
		writeln('	enterMode: CKEDITOR.ENTER_BR,');
		writeln('	toolbar :');
		writeln('	[');
		writeln('		["Bold","Italic","Underline","Strike"],');
		writeln('		["NumberedList","BulletedList","Blockquote"],');
		writeln('		["Link","Unlink"]');
		writeln('	]');
		writeln('});');
		writeln();
		writeln('</script>');
	}
}


$can_moderate = false;

if (http_post()) {
	$sid = http_post_int("sid", array("required" => false));
	$cid = http_post_int("cid", array("required" => false));
	$pid = http_post_int("pid", array("required" => false));
	$qid = http_post_int("qid", array("required" => false));
	$subject = clean_subject();
	list($clean_body, $dirty_body) = clean_body();
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
		$day = gmdate("Y-m-d", $story["time"]);
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
		print render_comment($subject, ($coward ? "" : $zid), $time, 0, $clean_body);
		writeln('</div>');
		writeln('</article>');
		writeln('</div>');

		print_post_box($sid, $cid, $pid, $qid, $subject, $dirty_body, $coward);

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
	$comment["comment"] = $clean_body;
	db_set_rec("comment", $comment);

	$comment = db_get_rec("comment", array("zid" => $zid, "time" => $time));
	send_notifications($cid, $comment);

	if ($sid != 0) {
		if (db_has_rec("story_history", array("sid" => $sid, "zid" => $auth_zid))) {
			$history = db_get_rec("story_history", array("sid" => $sid, "zid" => $auth_zid));
			$history["time"] = $history["last_time"];
			db_set_rec("story_history", $history);
		}
		header("Location: /story/$day/" . $story["ctitle"]);
	} elseif ($pid != 0) {
		if (db_has_rec("pipe_history", array("pid" => $pid, "zid" => $auth_zid))) {
			$history = db_get_rec("pipe_history", array("pid" => $pid, "zid" => $auth_zid));
			$history["time"] = $history["last_time"];
			db_set_rec("pipe_history", $history);
		}
		header("Location: /pipe/$pid");
	} elseif ($qid != 0) {
		if (db_has_rec("poll_history", array("qid" => $qid, "zid" => $auth_zid))) {
			$history = db_get_rec("poll_history", array("qid" => $qid, "zid" => $auth_zid));
			$history["time"] = $history["last_time"];
			db_set_rec("poll_history", $history);
		}
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
	print render_comment($comment["subject"], $zid, $comment["time"], $comment["cid"], $comment["comment"]);
	writeln('</div>');
	writeln('</article>');
	writeln('</div>');

} else {
	$subject = "";
}

print_post_box($sid, $cid, $pid, $qid, $subject, "", false);

writeln('</td>');
writeln('</tr>');
writeln('</table>');

print_footer();
