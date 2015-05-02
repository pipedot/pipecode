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

$code = http_get_string("code", array("required" => false, "len" => 6, "valid" => "[A-Z][a-z][0-9]"));
if ($code == "") {
	$code = $s2;
}
if (strlen($code) != 6 || !string_uses($code, "[A-Z][a-z][0-9]")) {
	die("invalid verification code");
}
$id = crypt_crockford_decode($code);

if (!db_has_rec("email_challenge", $id)) {
	die("wrong verification code");
}

print_header("Reset Password");
writeln('<hr>');
beg_main();
beg_form("/reset/$code");
writeln('<h1>Reset Password</h1>');

writeln('<p>Please choose a new password.</p>');
writeln('<table class="login">');
writeln('	<tr>');
writeln('		<td>Password</td>');
writeln('		<td><input name="password_1" type="password" autofocus required></td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td>Password (again)</td>');
writeln('		<td><input name="password_2" type="password" required></td>');
writeln('	</tr>');
writeln('</table>');

box_left("Reset");

end_form();
end_main();
print_footer();
