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

require_https($https_enabled);

$code = http_get_string("code", ["required" => false, "len" => 64, "valid" => "[0-9]abcdef"]);
if ($code == "") {
	$code = $s2;
}
if ($code == "") {
	print_header(["title" => "Verification Code", "form" => true]);
	writeln('<hr>');
	beg_main();
	beg_form("/verify", "get");
	writeln('<h1>' . get_text('Verification Code') . '</h1>');

	writeln('<table class="login">');
	writeln('	<tr>');
	writeln('		<td>' . get_text('Code') . '</td>');
	writeln('		<td><input name="code" type="text" autofocus required></td>');
	writeln('	</tr>');
	writeln('</table>');

	box_left("Verify");

	print_footer(["form" => true]);
	finish();
}

if (!db_has_rec("email_challenge", $code)) {
	fatal("Wrong verification code");
}
$email_challenge = db_find_rec("email_challenge", $code);
if ($email_challenge === false) {
	fatal("Wrong verification code");
}

$zid = strtolower($email_challenge["username"]) . "@$server_name";
$new = !is_local_user($zid);

if ($new) {
	print_header(["title" => "Set Password", "form" => true]);
} else {
	print_header(["title" => "Reset Password", "form" => true]);
}
writeln('<hr>');
beg_main();
beg_form();
if ($new) {
	writeln('<h1>' . get_text('Set Password') . '</h1>');
} else {
	writeln('<h1>' . get_text('Reset Password') . '</h1>');
}

writeln('<input type="hidden" name="code" value="' . $code . '">');
writeln('<p>' . get_text('Please choose a password.') . '</p>');
writeln('<table class="login">');
writeln('	<tr>');
writeln('		<td>' . get_text('Password') . '</td>');
writeln('		<td><input name="password_1" type="password" autofocus required></td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td>' . get_text('Password (again)') . '</td>');
writeln('		<td><input name="password_2" type="password" required></td>');
writeln('	</tr>');
writeln('</table>');

box_left("Save");

print_footer(["form" => true]);
