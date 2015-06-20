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

require_admin();

$zid = domain_to_zid($s2);
if (!is_local_user($zid)) {
	fatal("User not found");
}
$conf = db_get_conf("user_conf", $zid);

$admin = http_post_bool("admin", array("numeric" => true));
$developer = http_post_bool("developer", array("numeric" => true));
$editor = http_post_bool("editor", array("numeric" => true));

$conf["admin"] = $admin;
$conf["developer"] = $developer;
$conf["editor"] = $editor;
db_set_conf("user_conf", $conf, $zid);

header("Location: /user/");
