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

include("mail.php");

$verify = http_get_string("verify", array("required" => false, "len" => 64, "valid" => "[0-9]abcdef"));
if (strlen($verify) != 0 && strlen($verify) != 64) {
	die("invalid verify hash");
}
if ($verify != "") {
	$email_challenge = db_get_rec("email_challenge", array("challenge" => $verify));
	$zid = strtolower($email_challenge["username"]) . "@$server_name";
	if (!is_local_user($zid)) {
		die("no such user [$zid]");
	}
	$user_conf = db_get_conf("user_conf", $zid);
}

if ($verify != "") {
	print_header("Reset Password");
	writeln('<hr/>');
	beg_main();
	if ($https_enabled) {
		beg_form("https://$server_name/forgot?verify=$verify");
	} else {
		beg_form("/forgot?verify=$verify");
	}
	writeln('<h1>Reset Password</h1>');
	writeln('<table>');
	writeln('	<tr>');
	writeln('		<td colspan="2">Please choose a new password.</td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td style="padding-top: 8px; text-align: right">Password</td>');
	writeln('		<td style="padding-top: 8px"><input name="password_1" type="password"/></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td style="padding-bottom: 8px; text-align: right">Password (again)</td>');
	writeln('		<td style="padding-bottom: 8px"><input name="password_2" type="password"/></td>');
	writeln('	</tr>');
	writeln('</table>');
	box_left("Finish");
	end_form();
	end_main();
	print_footer();

	die();
}

print_header("Forgot Password");
writeln('<hr/>');
beg_main();
beg_form();
writeln('<h1>Forgot Password?</h1>');

writeln('<table>');
writeln('	<tr>');
writeln('		<td>Username</td>');
writeln('		<td><input name="username" type="text"/></td>');
writeln('	</tr>');
writeln('</table>');

box_left("Send");

end_form();
end_main();
print_footer();

