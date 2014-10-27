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

function print_post_box($type, $root_id, $subject, $dirty_body, $coward)
{
	global $auth_zid;
	global $auth_user;
	global $doc_root;

	beg_form();
	writeln('<input type="hidden" name="type" value="' . $type . '"/>');
	writeln('<input type="hidden" name="root_id" value="' . $root_id . '"/>');
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
	if ($auth_zid === "") {
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
		writeln('CKEDITOR.timestamp = "' . fs_time("$doc_root/www/lib/ckeditor/config.js") . '";');
		writeln('CKEDITOR.replace("comment",');
		writeln('{');
		writeln('	resize_enabled: false,');
		writeln('	enterMode: CKEDITOR.ENTER_BR,');
		writeln('	toolbar :');
		writeln('	[');
		writeln('		["Bold","Italic","Underline","Strike","Subscript","Superscript"],');
		writeln('		["NumberedList","BulletedList","Blockquote"],');
		writeln('		["Link","Unlink"]');
		writeln('	]');
		writeln('});');
		writeln();
		writeln('</script>');
	}
}
