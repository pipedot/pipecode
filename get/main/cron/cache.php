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

set_time_limit(14 * MINUTES);
header_text();

$cutoff = $now - 4 * WEEKS;
$row = sql("select cache_id, access_time, data_hash, url from cache where access_time < ? limit 100000", $cutoff);

for ($i = 0; $i < count($row); $i++) {
	$cache_id = $row[$i]["cache_id"];
	$time = date("Y-m-d H:i", $row[$i]["access_time"]);
	$hash = $row[$i]["data_hash"];
	$url = $row[$i]["url"];

	writeln("time [$time] hash [$hash] cache_id [$cache_id] url [$url]");
	drive_unlink($hash, $cache_id, TYPE_CACHE);
	db_del_rec("cache", $cache_id);
}
