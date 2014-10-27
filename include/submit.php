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

function print_submit_box($title, $dirty_body, $body, $tid, $preview)
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
		$a["body"] = $body;
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
	if ($auth_zid === "") {
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

