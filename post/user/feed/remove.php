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

if ($zid !== $auth_zid) {
	die("not your page");
}

$fid = http_get_int("fid");
$feed = db_get_rec("feed", $fid);

db_del_rec("feed_user", array("zid" => $auth_zid, "fid" => $fid));

$row = sql("select count(zid) as user_count from feed_user where fid = ?", $fid);
$count = $row[0]["user_count"];
if ($count == 0) {
	sql("delete from feed_item where fid = ?", $fid);
	sql("delete from feed where fid = ?", $fid);
}

header("Location: edit");
