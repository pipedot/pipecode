<?
//
// Pipecode - distributed social network
// Copyright (C) 2014-2016 Bryan Beicker <bryan@pipedot.org>
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

$username = http_post_string("username", array("len" => 20, "valid" => "[a-z][A-Z][0-9]"));
$password = http_post_string("password", array("len" => 64, "valid" => "[KEYBOARD]"));
$referer = http_get_string("referer", array("required" => false, "len" => 200, "valid" => "[a-z][A-Z][0-9].+-_/?&#=;~"));
$server = http_get_string("server", array("required" => false, "len" => 100, "valid" => "[a-z][0-9].-"));

$zid = strtolower($username) . "@$server_name";
$user_conf = db_get_conf("user_conf", $zid);
if ($user_conf["password"] != crypt_sha256($password . $user_conf["salt"])) {
	fatal("Wrong Password", "lock", "Login Failed", "Wrong Password");
}

$key = random_hash();
$login = db_new_rec("login");
$login["zid"] = $zid;
$login["login_key"] = $key;
$login["agent_id"] = get_agent_id();
$login["ip_id"] = get_ip_id();
$login["os_id"] = get_os_id();
db_set_rec("login", $login);

setcookie("auth", "zid=$zid&key=$key", time() + $auth_expire, "/", ".$server_name");

if ($referer != "") {
	if ($server != "") {
		header("Location: $protocol://$server$referer");
	} else {
		header("Location: $referer");
	}
} else {
	header("Location: ./");
}
