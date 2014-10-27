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

include("captcha.php");
include("mail.php");

if (!$server_conf["sign_up_enabled"]) {
	die("sign up not enabled");
}

$verify = http_get_string("verify", array("required" => false, "len" => 64, "valid" => "[0-9]abcdef"));
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
	$password_1 = http_post_string("password_1", array("len" => 64, "valid" => "[KEYBOARD]"));
	$password_2 = http_post_string("password_2", array("len" => 64, "valid" => "[KEYBOARD]"));

	if (strlen($password_1) < 6) {
		die("password too short");
	}
	if ($password_1 != $password_2) {
		die("passwords do not match");
	}

	$salt = random_hash();
	$password = crypt_sha256("$password_1$salt");

	$user_conf = array();
	$user_conf["password"] = $password;
	$user_conf["salt"] = $salt;
	$user_conf["email"] = $email_challenge["email"];
	$user_conf["joined"] = time();
	db_set_conf("user_conf", $user_conf, $zid);

	db_del_rec("email_challenge", $email_challenge["challenge"]);

	print_header("User Created");
	writeln('<h1>User Created</h1>');
	writeln('<p>Welcome to ' . $server_title . '!</p>');
	writeln('<p>' . $zid . "</p>");
	print_footer();
	die();
}
$username = http_post_string("username", array("len" => 20, "valid" => "[a-z][A-Z][0-9]"));
$mail_1 = http_post_string("mail_1", array("len" => 50, "valid" => "[a-z][A-Z][0-9]@.-_+"));
$mail_2 = http_post_string("mail_2", array("len" => 50, "valid" => "[a-z][A-Z][0-9]@.-_+"));
$answer = http_post_string("answer", array("required" => false));

$username = strtolower($username);
if (string_uses(substr($username, 0, 1), "[0-9]")) {
	die("user_name may not start with a number [$username]");
}
if (strlen($username) < 3) {
	die("user_name must be at least 3 characters [$username]");
}

$rfc_2142 = array("info", "marketing", "sales", "support", "abuse", "noc", "security", "postmaster", "hostmaster", "usenet", "news", "webmaster", "www", "uucp", "ftp");
if (in_array($username, $rfc_2142)) {
	die("username is reserved [$username]");
}
$reserved_usernames = array("admin", "administrator", "anonymous", "blog", "bugs", "cash", "code", "donate", "feed", "feedback", "forum", "git", "img", "legal", "list", "lists", "mail", "pipe", "pipecode", "pipedot", "pipeline", "root", "scm", "ssladmin", "wiki");
if (in_array($username, $reserved_usernames)) {
	die("username is reserved [$username]");
}
if ($mail_1 != $mail_2) {
	die("email addresses do not match [$mail_1] [$mail_2]");
}
$a = explode("@", $mail_1);
if (count($a) != 2) {
	die("invalid email address [$mail_1]");
}
if (strlen($a[0]) == 0) {
	die("invalid username in email address [$mail_1]");
}
if (strlen($a[1]) < 3 || !string_has($a[1], ".")) {
	die("invalid domain in email address [$mail_1]");
}
if (is_local_user("$username@$server_name")) {
	die("username already exists [$username]");
}

if (!captcha_verify($answer)) {
	die("captcha failed");
}

print_header("Email Sent");
writeln('<h1>Email Sent</h1>');
writeln('<p>Please visit the link in the email within 3 days to activate your account.</p>');
print_footer();

$ip = $_SERVER["REMOTE_ADDR"];
$hash = crypt_sha256(rand());

$email_challenge = array();
$email_challenge["challenge"] = $hash;
$email_challenge["username"] = $username;
$email_challenge["email"] = $mail_1;
$email_challenge["expires"] = time() + 86400 * 3;
db_set_rec("email_challenge", $email_challenge);

$subject = "Welcome to $server_title";
$body = "Someone, probably you, has registered an account \"$username\" ";
$body .= "with this email address.\n";
$body .= "\n";
$body .= "In order to verify your email, you must visit the following link:\n";
$body .= "\n";
if ($https_enabled) {
	$body .= "https://$server_name/sign_up?verify=$hash\n";
} else {
	$body .= "http://$server_name/sign_up?verify=$hash\n";
}
$body .= "\n";
$body .= "This confirmation code will expire in 3 days.\n";

send_mail($mail_1, $subject, $body);
