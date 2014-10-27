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

if (!$auth_user["admin"]) {
	die("not an admin");
}

$theme = http_post_string("theme", array("len" => 50, "valid" => "[a-z][0-9]-_"));
$sign_up_enabled = http_post_bool("sign_up_enabled", array("numeric" => true));
$submit_enabled = http_post_bool("submit_enabled", array("numeric" => true));

$server_conf["theme"] = $theme;
$server_conf["sign_up_enabled"] = $sign_up_enabled;
$server_conf["submit_enabled"] = $submit_enabled;
db_set_conf("server_conf", $server_conf);

header("Location: /menu/");
