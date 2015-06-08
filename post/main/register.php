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

$code = random_hash();

$email_challenge = db_new_rec("email_challenge");
$email_challenge["code"] = $code;
$email_challenge["email"] = $mail_1;
$email_challenge["expires"] = time() + DAYS * 3;
$email_challenge["username"] = $username;
db_set_rec("email_challenge", $email_challenge);

$subject = "Welcome to $server_title";
$body = "To create your account, use the verification code:\n";
$body .= "\n";
$body .= "$code\n";
$body .= "\n";
$body .= "Or visit the following link:\n";
$body .= "\n";
$body .= "$protocol://$server_name/verify/$code\n";
$body .= "\n";
$body .= "This code will expire in 3 days.\n";

send_mail($mail_1, $subject, $body);

header("Location: /verify");
