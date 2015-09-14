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

function print_post_box($root_id, $subject, $dirty_body, $coward)
{
	global $auth_zid;
	global $auth_user;
	global $doc_root;
	global $remote_ip;
	global $protocol;
	global $server_name;

	if (db_has_rec("ban_ip", $remote_ip)) {
		$banned = true;
		$ban_ip = db_get_rec("ban_ip", $remote_ip);
		$short_code = crypt_crockford_encode($ban_ip["short_id"]);
		writeln('<div class="balloon">');
		writeln('<h1>Banned IP Address</h1>');
		writeln("<p>Your IP address [<b>$remote_ip</b>] is banned for sending junk messages. Anonymous posting is disabled.</p>");
		writeln("<p>An example junk message can be found here: <a href=\"$protocol://$server_name/$short_code\">#$short_code</a></p>");
		writeln('</div>');
		if ($auth_zid === "") {
			end_main();
			print_footer();
			die();
		}
	} else {
		$banned = false;
	}

	beg_form();
	writeln('<input type="hidden" name="root_id" value="' . $root_id . '">');
	writeln('<div class="reply">');
	writeln('<div class="dialog-title">Post Comment</div>');
	writeln('<div class="dialog-body">');
	writeln('<table>');
	writeln('	<tr>');
	writeln('		<td>Subject</td>');
	writeln('		<td colspan="2"><input name="subject" type="text" value="' . $subject . '" required></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td>Comment</td>');
	writeln('		<td colspan="2"><textarea name="comment" required>' . $dirty_body . '</textarea></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	if ($auth_zid === "") {
		$question = captcha_challenge();
		writeln('		<td>Captcha</td>');
		writeln('		<td><table><tr><td>' . $question . '</td><td><input name="answer" type="text" style="margin-left: 8px; width: 100px"></td></tr></table></td>');
	} else {
		writeln('		<td></td>');
		if ($banned) {
			writeln('		<td></td>');
		} else {
			writeln('		<td><label><input name="coward" type="checkbox"' . ($coward ? ' checked' : '') . '>Post Anonymously</label></td>');
		}
	}
	writeln('		<td><input name="post" type="submit" value="Post"> <input name="preview" type="submit" value="Preview"></td>');
	writeln('	</tr>');
	writeln('</table>');
	writeln('</div>');
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
