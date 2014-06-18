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

if (http_post()) {
	$username = http_post_string("username", array("len" => 20, "valid" => "[a-z][A-Z][0-9]"));
	$password = http_post_string("password", array("len" => 64, "valid" => "[KEYBOARD]"));
	$referer = http_get_string("referer", array("required" => false, "len" => 200, "valid" => "[a-z][A-Z][0-9].+-_/?&#=;~"));

	$zid = strtolower($username) . "@$server_name";
	$user_conf = db_get_conf("user_conf", $zid);
	if ($user_conf["password"] != crypt_sha256($password . $user_conf["salt"])) {
		die("wrong password");
	}

	$expire = time() + $auth_expire;
	$cookie = "expire=$expire&zid=$zid";
	$cookie .= "&hash=" . crypt_sha256($auth_key . $cookie);
	setcookie("auth", $cookie, time() + $auth_expire, "/", ".$server_name");
	if ($referer != "") {
		header("Location: $referer");
	} else {
		header("Location: ./");
	}

}

if ($protocol != "https" && $https_enabled) {
	header("Location: https://$server_name/sign_in");
	die();
}

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
writeln('<input type="submit" value="Sign In"/>');
writeln('<a href="/forgot">Forgot Password?</a>');
end_form();

end_main();
print_footer();
