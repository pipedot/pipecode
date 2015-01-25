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

include("render.php");

if (!$auth_user["admin"] && !$auth_user["editor"]) {
	die("not an editor or admin");
}

$ip = urldecode($s2);
if (!string_uses($ip, "[0-9].:abcdef")) {
	die("invalid ip address");
}
$ban_ip = db_get_rec("ban_ip", $ip);

db_del_rec("ban_ip", $ip);

header("Location: /ban_ip/");
