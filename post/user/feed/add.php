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

include("feed.php");

if ($zid !== $auth_zid) {
	die("not your page");
}

$col = http_get_int("col");
if ($col < 0 || $col > 2) {
	die("invalid col [$col]");
}

$fid = http_post_int("fid", array("required" => false));
$uri = http_post_string("uri", array("required" => false, "len" => 100, "valid" => "[a-z][A-Z][0-9]~@#$%&()-_=+[];:,./?"));

if ($fid == 0) {
	if ($uri == "") {
		die("no feed uri given");
	}
	$fid = add_feed($uri);
}
if (!db_has_rec("feed", $fid)) {
	die("fid not found [$fid]");
}
if (db_has_rec("feed_user", array("zid" => $auth_zid, "fid" => $fid))) {
	die("feed [$fid] is already on your page");
}

$row = sql("select max(pos) as max_pos from feed_user where zid = ? and col = ?", $auth_zid, $col);
$pos = $row[0]["max_pos"] + 1;

$feed_user = array();
$feed_user["zid"] = $auth_zid;
$feed_user["fid"] = $fid;
$feed_user["col"] = $col;
$feed_user["pos"] = $pos;
db_set_rec("feed_user", $feed_user);
header("Location: edit");
