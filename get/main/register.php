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

include("captcha.php");
include("mail.php");

if (!$server_conf["register_enabled"]) {
	die("register not enabled");
}

/*$verify = http_get_string("verify", array("required" => false, "len" => 64, "valid" => "[0-9]abcdef"));
if (strlen($verify) != 0 && strlen($verify) != 64) {
	die("invalid verify hash");
}
if ($verify != "") {
	$email_challenge = db_get_rec("email_challenge", array("challenge" => $verify));
	$zid = strtolower($email_challenge["username"]) . "@$server_name";
	if (is_local_user($zid)) {
		die("username already exists [$zid]");
	}
}

if ($verify != "") {
	print_header("Email Validated");
	writeln('<hr>');
	beg_main();
	if ($https_enabled) {
		beg_form("https://$server_name/sign_up?verify=$verify");
	} else {
		beg_form("/sign_up?verify=$verify");
	}
	writeln('<h1>Email Validated</h1>');
	writeln('<table>');
	writeln('	<tr>');
	writeln('		<td colspan="2">Please choose a password.</td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td style="padding-top: 8px; text-align: right">Password</td>');
	writeln('		<td style="padding-top: 8px"><input name="password_1" type="password"></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td style="padding-bottom: 8px; text-align: right">Password (again)</td>');
	writeln('		<td style="padding-bottom: 8px"><input name="password_2" type="password"></td>');
	writeln('	</tr>');
	writeln('</table>');
	box_left("Finish");
	end_form();
	end_main();
	print_footer();

	die();
}
*/

print_header("Register");
writeln('<hr>');
beg_main();

if ($https_enabled) {
	beg_form("https://$server_name/register");
} else {
	beg_form("/register");
}

?>
<h1>Create Account</h1>
<table>
	<tr>
		<td class="top">
			<table>
				<tr>
					<td colspan="2"><h3>Enter your information:</h3></td>
				</tr>
				<tr>
					<td class="right">Username</td>
					<td><input name="username" type="text" placeholder="Only a-z,0-9" autofocus required></td>
				</tr>
				<tr>
					<td class="right">Email</td>
					<td><input name="mail_1" type="email" size="40" required></td>
				</tr>
				<tr>
					<td class="right">Email (again)</td>
					<td><input name="mail_2" type="email" size="40" required></td>
				</tr>
			</table>
		</td>
		<td class="top">
			<table>
				<tr>
					<td><h3>Prove yourself:</h3></td>
				</tr>
				<tr>
					<td><table><tr><td><?= captcha_challenge(); ?></td><td><input name="answer" type="text"></td></tr></table></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?

box_left("Register");

end_form();
end_main();
print_footer();
