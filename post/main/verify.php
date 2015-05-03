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

//if (strlen($s2) != 6 || !string_uses($s2, "[A-Z][a-z][0-9]")) {
//	die("invalid verification code");
//}

$code = http_post_string("code", ["len" => 6, "valid" => "[A-Z][a-z][0-9]"]);
$password_1 = http_post_string("password_1", ["len" => 64, "valid" => "[KEYBOARD]"]);
$password_2 = http_post_string("password_2", ["len" => 64, "valid" => "[KEYBOARD]"]);

$id = crypt_crockford_decode($code);
if ($password_1 != $password_2) {
	die("passwords do not match");
}

$email_challenge = db_find_rec("email_challenge", $id);
if ($email_challenge === false) {
	die("wrong verification code");
}

$zid = strtolower($email_challenge["username"]) . "@$server_name";
$new = !is_local_user($zid);
$salt = random_hash();
$password = crypt_sha256("$password_1$salt");

if ($new) {
	$user_conf = [];
	$user_conf["email"] = $email_challenge["email"];
	$user_conf["joined"] = time();
	$user_conf["zid"] = $zid;
} else {
	$user_conf = db_get_conf("user_conf", $zid);
}
$user_conf["password"] = $password;
$user_conf["salt"] = $salt;
db_set_conf("user_conf", $user_conf, $zid);

db_del_rec("email_challenge", $id);
sql("delete from email_challenge where expires < ?", time());

header("Location: /login");
