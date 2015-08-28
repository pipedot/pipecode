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

include("drive.php");
include("avatar.php");

require_mine();

$avatar = item_request(TYPE_AVATAR);
$avatar_code = $avatar["short_code"];

drive_unlink($avatar["hash_64"], $avatar["avatar_id"], TYPE_AVATAR);
drive_unlink($avatar["hash_128"], $avatar["avatar_id"], TYPE_AVATAR);
drive_unlink($avatar["hash_256"], $avatar["avatar_id"], TYPE_AVATAR);
db_del_rec("short", $avatar["avatar_id"]);
db_del_rec("avatar", $avatar["avatar_id"]);

if ($user_conf["avatar_id"] == $avatar["avatar_id"]) {
	$row = sql("select avatar_id from avatar where zid = ? and avatar_id <> ? order by time desc", $auth_zid, $avatar["avatar_id"]);
	if (count($row) > 0) {
		$avatar_id = $row[0]["avatar_id"];
	} else {
		$avatar_id = create_identicon_avatar($auth_zid);
	}
	$user_conf["avatar_id"] = $avatar_id;
	db_set_conf("user_conf", $user_conf, $auth_zid);
}

header("Location: /avatar/");
