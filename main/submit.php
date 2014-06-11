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
include("story.php");
include("captcha.php");


function print_submit_box($title, $dirty_body, $story, $tid, $preview)
{
	global $auth_zid;
	global $auth_user;

	print_header("Submit Story");

	print_left_bar("main", "pipe");
	beg_main("cell");

	if ($preview) {
		$a["zid"] = $auth_zid;
		$topic = db_get_rec("topic", $tid);
		$a["title"] = $title;
		$a["topic"] = $topic["topic"];
		$a["icon"] = $topic["icon"];
		$a["story"] = $story;
		writeln('<h1>Preview</h1>');
		writeln('<p>Check your links before you post!</p>');
		print_article($a);
	}

	beg_form();
	writeln('<div class="dialog_title">Submit Story</div>');
	writeln('<div class="dialog_body">');

	writeln('<table class="fill" style="padding: 0px">');
	writeln('	<tr>');
	writeln('		<td style="width: 80px">Title</td>');
	writeln('		<td colspan="2"><input name="title" type="text" value="' . $title . '" required="required"/></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td style="width: 80px">Topic</td>');
	writeln('		<td colspan="2">');
	writeln('			<select name="tid">');
	$topics = db_get_list("topic", "topic");
	$k = array_keys($topics);
	for ($i = 0; $i < count($topics); $i++) {
		$topic = $topics[$k[$i]];
		if ($topic["tid"] == $tid) {
			writeln('				<option value="' . $topic["tid"] . '" selected="selected">' . $topic["topic"] . '</option>');
		} else {
			writeln('				<option value="' . $topic["tid"] . '">' . $topic["topic"] . '</option>');
		}
	}
	writeln('			</select>');
	writeln('		</td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td style="width: 80px; vertical-align: top; padding-top: 12px">Story</td>');
	writeln('		<td colspan="2"><textarea name="story" style="height: 200px" required="required">' . $dirty_body . '</textarea></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	if ($auth_zid == "") {
		$question = captcha_challenge();
		writeln('		<td>Captcha</td>');
		writeln('		<td><table><tr><td>' . $question . '</td><td><input name="answer" type="text" style="margin-left: 8px; width: 100px"/></td></tr></table></td>');
		writeln('		<td class="right"><input type="submit" value="Submit"/> <input name="preview" type="submit" value="Preview"/></td>');
	} else {
		writeln('		<td colspan="3" class="right"><input type="submit" value="Submit"/> <input name="preview" type="submit" value="Preview"/></td>');
	}
	writeln('	</tr>');
	writeln('</table>');
	writeln('</div>');

	end_form();
	end_main();
	print_footer();
}


if (http_post()) {
	$title = clean_subject();
	list($clean_body, $dirty_body) = clean_body();
	$tid = http_post_int("tid");
	$answer = http_post_string("answer", array("required" => false));
	$time = time();

	if ($auth_zid == "" && !captcha_verify($answer)) {
		die("captcha failed");
	}

	$topic = db_get_rec("topic", $tid);

	if (http_post("preview")) {
		print_submit_box($title, $dirty_body, $clean_body, $tid, true);
		die();
	}

	$pipe = array();
	$pipe["pid"] = 0;
	$pipe["tid"] = $tid;
	$pipe["zid"] = $auth_zid;
	$pipe["editor"] = "";
	$pipe["title"] = $title;
	$pipe["ctitle"] = clean_url($title);
	$pipe["icon"] = $topic["icon"];
	$pipe["time"] = $time;
	$pipe["closed"] = 0;
	$pipe["reason"] = "";
	$pipe["story"] = $clean_body;

	db_set_rec("pipe", $pipe);

	$pipe = db_get_rec("pipe", array("zid" => $auth_zid, "time" => $time));
	$pid = $pipe["pid"];

	header("Location: /pipe/$pid");
	die();
}

print_submit_box("", "", "", 13, false);
