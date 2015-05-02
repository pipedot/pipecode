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

$username = http_post_string("username", array("len" => 20, "valid" => "[a-z][A-Z][0-9]"));

$zid = strtolower($username) . "@$server_name";
if (!is_local_user($zid)) {
	die("no such user [$zid]");
}
$user_conf = db_get_conf("user_conf", $zid);

$id = rand(0, pow(32, 6));
$code = string_pad(crypt_crockford_encode($id), 6);

sql("delete from email_challenge where expires < ?", time());
if (db_has_rec("email_challenge", ["username" => $username])) {
	db_del_rec("email_challenge", ["username" => $username]);
}

$email_challenge = db_new_rec("email_challenge");
$email_challenge["code"] = $id;
$email_challenge["email"] = $user_conf["email"];
$email_challenge["expires"] = time() + DAYS * 3;
$email_challenge["username"] = $username;
db_set_rec("email_challenge", $email_challenge);

$subject = "Forgot Password";
$body = "Did you forget your password for \"$username\" on $server_name?\n";
$body .= "\n";
$body .= "To reset your password, use the verification code:\n";
$body .= "\n";
$body .= "$code\n";
$body .= "\n";
$body .= "Or visit the following link:\n";
$body .= "\n";
$body .= "$protocol://$server_name/reset/$code\n";
$body .= "\n";
$body .= "This code will expire in 3 days.\n";

send_mail($user_conf["email"], $subject, $body);

header("Location: /verify");
