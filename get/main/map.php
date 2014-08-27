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

$short_code = $s2;
if (!string_uses($s2, "[A-Z][a-z][0-9]")) {
	die("invalid short code [$short_code]");
}
$short_id = crypt_crockford_decode($short_code);
if (!db_has_rec("short", $short_id)) {
	die("short code not found [$short_code]");
}

geo_init();
$row = sql("select remote_ip, count(remote_ip) as hits from short_view where short_id = ? group by remote_ip", $short_id);
for ($i = 0; $i < count($row); $i++) {
	$geo = geo_ip($row[$i]["remote_ip"]);
	if (!empty($geo["latitude"]) && !empty($geo["longitude"])) {
		//var_dump($geo);
		geo_dot($geo["latitude"], $geo["longitude"]);
	}
	//geo_dot(29.4241, -98.4936);		// san antonio
	//geo_dot(37.5155, -121.8962);		// freemont
	//geo_dot(32.7831, -96.8067);		// dallas
	//geo_dot(21.3, -157.816667);		// honolulu
}
//die();
geo_plot();