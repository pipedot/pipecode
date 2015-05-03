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

require_https($https_enabled);

if (!$server_conf["register_enabled"]) {
	die("register not enabled");
}

print_header("Register");
writeln('<hr>');
beg_main();
beg_form();

writeln('<h1>Create Account</h1>');
writeln('<table>');
writeln('	<tr>');
writeln('		<td class="top">');
writeln('			<table>');
writeln('				<tr>');
writeln('					<td colspan="2"><h3>Enter your information:</h3></td>');
writeln('				</tr>');
writeln('				<tr>');
writeln('					<td class="right">Username</td>');
writeln('					<td><input name="username" type="text" placeholder="Only a-z,0-9" autofocus required></td>');
writeln('				</tr>');
writeln('				<tr>');
writeln('					<td class="right">Email</td>');
writeln('					<td><input name="mail_1" type="email" size="40" required></td>');
writeln('				</tr>');
writeln('				<tr>');
writeln('					<td class="right">Email (again)</td>');
writeln('					<td><input name="mail_2" type="email" size="40" required></td>');
writeln('				</tr>');
writeln('			</table>');
writeln('		</td>');
writeln('		<td class="top">');
writeln('			<table>');
writeln('				<tr>');
writeln('					<td><h3>Prove yourself:</h3></td>');
writeln('				</tr>');
writeln('				<tr>');
writeln('					<td><table><tr><td>' . captcha_challenge() . '</td><td><input name="answer" type="text"></td></tr></table></td>');
writeln('				</tr>');
writeln('			</table>');
writeln('		</td>');
writeln('	</tr>');
writeln('</table>');

box_left("Register");

end_form();
end_main();
print_footer();
