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

include("feed.php");
include("clean.php");
include("image.php");
include("drive.php");

require_mine();

$feed_url = http_post_string("feed_url", array("len" => 100, "valid" => "[a-z][A-Z][0-9]~@#$%&()-_=+[];:,./?"));

$feed_id = add_feed($feed_url);
$feed = db_find_rec("feed", $feed_id);
if ($feed == null) {
	die("feed_id not found [$feed_id]");
}
if (db_has_rec("reader_user", ["zid" => $auth_zid, "feed_id" => $feed_id])) {
	die("feed [$feed_id] is already in your list");
}

$reader_user = db_new_rec("reader_user");
$reader_user["zid"] = $auth_zid;
$reader_user["feed_id"] = $feed_id;
$reader_user["name"] = $feed["title"];
$reader_user["slug"] = $feed["slug"];
db_set_rec("reader_user", $reader_user);

header("Location: /reader/");

