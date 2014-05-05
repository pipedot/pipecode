<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
//
// This file is part of Pipecode.
//
// Pipecode is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Pipecode is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Pipecode.  If not, see <http://www.gnu.org/licenses/>.
//

include("feed.php");

header("Content-Type: text/plain");
header_expires();

$row = run_sql("select fid, uri, time from feed");
for ($i = 0; $i < count($row); $i++) {
	$fid = $row[$i]["fid"];
	$uri = $row[$i]["uri"];
	$time = $row[$i]["time"];

	if (time() > ($time + 60 * 5)) {
		print "downloading fid [$fid] uri [$uri] ";
		$data = download_feed($uri);
		print "len [" . strlen($data) . "]\n";
		save_feed($fid, $data);
	}
}

print "done";
