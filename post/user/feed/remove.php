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

if ($zid !== $auth_zid) {
	die("not your page");
}

$feed_id = http_get_int("feed_id");
$feed = db_get_rec("feed", $feed_id);

db_del_rec("feed_user", array("zid" => $auth_zid, "feed_id" => $feed_id));

//$row = sql("select count(zid) as user_count from feed_user where feed_id = ?", $feed_id);
//$count = $row[0]["user_count"];
//if ($count == 0) {
//	sql("delete from feed_item where feed_id = ?", $feed_id);
//	sql("delete from feed where feed_id = ?", $feed_id);
//}

header("Location: edit");
