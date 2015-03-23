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

$short_code = $slug;
$short_id = crypt_crockford_decode($short_code);

$short = db_find_rec("short", $short_id);
if ($short === false) {
	die("unable to find record [$short_code]");
}

$link = item_link($short["type"], $short_id);

print_header();
beg_main();

writeln('<h1>Short Link</h1>');

dict_beg();
dict_row("Code", $short_code);
dict_row("Type", $short["type"]);
dict_row("URL", '<a href="' . $link . '">' . $link . '</a>');
dict_end();

beg_tab();
writeln('	<tr>');
writeln('		<td style="background-color: #ffffff; text-align: center"><img class="map" src="/map/' . $short_code . '"/></td>');
writeln('	</tr>');
end_tab();

$country = array();
$city = array();
$timezone = array();
$row = sql("select remote_ip, count(remote_ip) as hits from short_view where short_id = ? group by remote_ip", $short_id);
for ($i = 0; $i < count($row); $i++) {
	$geo = geo_ip($row[$i]["remote_ip"]);
	$c = $geo["country"];
	if (!empty($c)) {
		if (array_key_exists($c, $country)) {
			$country[$c] += $row[$i]["hits"];
		} else {
			$country[$c] = $row[$i]["hits"];
		}
	}
	$c = $geo["city"];
	if (!empty($c)) {
		if (array_key_exists($c, $city)) {
			$city[$c] += $row[$i]["hits"];
		} else {
			$city[$c] = $row[$i]["hits"];
		}
	}
	$t = $geo["timezone"];
	if (!empty($t)) {
		if (array_key_exists($t, $timezone)) {
			$timezone[$t] += $row[$i]["hits"];
		} else {
			$timezone[$t] = $row[$i]["hits"];
		}
	}
}

arsort($country);
arsort($city);
arsort($timezone);

$k = array_keys($country);
dict_beg("Country");
for ($i = 0; $i < count($country); $i++) {
	dict_row($k[$i], $country[$k[$i]]);
}
dict_end();

$k = array_keys($city);
dict_beg("City");
for ($i = 0; $i < count($city); $i++) {
	dict_row($k[$i], $city[$k[$i]]);
}
dict_end();

$k = array_keys($timezone);
dict_beg("Timezone");
for ($i = 0; $i < count($timezone); $i++) {
	dict_row($k[$i], $timezone[$k[$i]]);
}
dict_end();

end_main();
print_footer();
