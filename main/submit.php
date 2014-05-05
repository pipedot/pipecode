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


function print_submit_box($title, $body, $story, $tid, $preview)
{
	global $auth_zid;
	global $auth_user;

	print_header("Submit Story");

	writeln('<table class="fill">');
	writeln('<tr>');
	writeln('<td class="left_col">');
	print_left_bar("main", "pipe");
	writeln('</td>');
	writeln('<td class="fill">');

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

	writeln('<form method="post">');
	writeln('<div class="dialog_title">Submit Story</div>');
	writeln('<div class="dialog_body">');

	writeln('<table class="fill" style="padding: 0px">');
	writeln('	<tr>');
	writeln('		<td style="width: 80px">Title</td>');
	writeln('		<td><input name="title" type="text" value="' . $title . '" required="required"/></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td style="width: 80px">Topic</td>');
	writeln('		<td>');
	writeln('			<select name="tid">');
	$topics = db_get_list("topic", "topic", array("promoted" => 1));
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
	writeln('		<td><textarea name="story" style="height: 200px" required="required">' . $body . '</textarea></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td class="right" colspan="2"><input type="submit" value="Submit"/> <input name="preview" type="submit" value="Preview"/></td>');
	writeln('	</tr>');
	writeln('</table>');
	writeln('</div>');
	writeln('</form>');

	writeln('</td>');
	writeln('</tr>');
	writeln('</table>');

	print_footer();
}


if (http_post()) {
	$title = http_post_string("title", array("len" => 100, "valid" => "[a-z][A-Z][0-9]`~!@#$%^&*()_+-={}|[]\\:\";',./? "));
	$body = http_post_string("story", array("len" => 64000, "valid" => "[ALL]"));
	$tid = http_post_int("tid");
	$time = time();

	$topic = db_get_rec("topic", $tid);

	$title = clean_entities($title);
	$new_body = str_replace("\n", "<br>", $body);
	$new_body = clean_html($new_body);

	if (http_post("preview")) {
		print_submit_box($title, $body, $new_body, $tid, true);
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
	$pipe["story"] = $new_body;

	db_set_rec("pipe", $pipe);

	$pipe = db_get_rec("pipe", array("zid" => $auth_zid, "time" => $time));
	$pid = $pipe["pid"];

	header("Location: /pipe/$pid");
	die();
}

print_submit_box("", "", "", 13, false);
