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

include("feed.php");
include("clean.php");
include("image.php");
include("drive.php");

set_time_limit(14 * MINUTES);
header_text();
header_expires();

$row = sql("select feed_id, uri, time from feed");
for ($i = 0; $i < count($row); $i++) {
	$feed_id = $row[$i]["feed_id"];
	$uri = $row[$i]["uri"];
	$time = $row[$i]["time"];

	if (time() > ($time + 60 * 5)) {
		print "downloading feed_id [$feed_id] uri [$uri] ";
		$data = download_feed($uri);
		//$data = http_slurp($uri);
		print "len [" . strlen($data) . "]\n";
		save_feed($feed_id, $data);
	}
}

print "done";
