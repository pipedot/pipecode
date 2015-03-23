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

if ($protocol != "https" && $https_enabled) {
	header("Location: https://$server_name/sign_in");
	die();
}

expire_auth();
print_header("Sign In");

writeln('<hr/>');
beg_main();
writeln('<h1>Sign In</h1>');

if ($https_enabled) {
	beg_form("https://$server_name/sign_in");
} else {
	beg_form("/sign_in");
}

writeln('<table>');
writeln('	<tr>');
writeln('		<td style="text-align: right">Username</td>');
writeln('		<td><input name="username" type="text" required="required"/></td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td style="padding-bottom: 8px; text-align: right">Password</td>');
writeln('		<td style="padding-bottom: 8px"><input name="password" type="password" required="required"/></td>');
writeln('	</tr>');
writeln('</table>');

box_left('<input type="submit" value="Sign In"/> <a href="/forgot">Forgot Password?</a>');

end_form();
end_main();
print_footer();
