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

function short_redirect($short_code)
{
	global $auth_zid;
	global $remote_ip;

	$short_id = crypt_crockford_decode($short_code);
	if (db_has_rec("short", $short_id)) {
		$short = db_get_rec("short", $short_id);

		$short_view = db_new_rec("short_view");
		$short_view["short_id"] = $short_id;
		if (empty($_SERVER["HTTP_USER_AGENT"])) {
			$short_view["agent"] = "";
		} else {
			$short_view["agent"] = $_SERVER["HTTP_USER_AGENT"];
		}
		if (empty($_SERVER["HTTP_REFERER"])) {
			$short_view["referer"] = "";
		} else {
			$short_view["referer"] = $_SERVER["HTTP_REFERER"];
		}
		$short_view["remote_ip"] = $remote_ip;
		$short_view["zid"] = $auth_zid;
		$short_view["time"] = time();
		db_set_rec("short_view", $short_view);

		header("Location: " . item_link($short["type"], $short["item_id"], $short_code));
		die();
	}
}


function short_info($short_code)
{
	global $protocol;
	global $server_name;

	$short_id = crypt_crockford_decode($short_code);
	if (db_has_rec("short", $short_id)) {
		$short = db_get_rec("short", $short_id);
		$link = item_link($short["type"], $short["item_id"]);
		print_header();
		beg_main();

		writeln('<h1>Short Link</h1>');
		beg_tab();
		writeln('	<tr>');
		writeln('		<td>Code</td>');
		writeln('		<td class="right">' . $short_code . '</td>');
		writeln('	</tr>');
		writeln('	<tr>');
		writeln('		<td>Type</td>');
		writeln('		<td class="right">' . $short["type"] . '</td>');
		writeln('	</tr>');
		writeln('	<tr>');
		writeln('		<td>Item</td>');
		writeln('		<td class="right">' . $short["item_id"] . '</td>');
		writeln('	</tr>');
		writeln('	<tr>');
		writeln('		<td>URL</td>');
		writeln('		<td class="right"><a href="' . $link . '">' . $link . '</a></td>');
		writeln('	</tr>');
		end_tab();

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
		beg_tab("Country", array("colspan" => 2));
		for ($i = 0; $i < count($country); $i++) {
			writeln('	<tr>');
			writeln('		<td>' . $k[$i] . '</td>');
			writeln('		<td class="right">' . $country[$k[$i]] . '</td>');
			writeln('	</tr>');
		}
		end_tab();

		$k = array_keys($city);
		beg_tab("City", array("colspan" => 2));
		for ($i = 0; $i < count($city); $i++) {
			writeln('	<tr>');
			writeln('		<td>' . $k[$i] . '</td>');
			writeln('		<td class="right">' . $city[$k[$i]] . '</td>');
			writeln('	</tr>');
		}
		end_tab();

		$k = array_keys($timezone);
		beg_tab("Timezone", array("colspan" => 2));
		for ($i = 0; $i < count($timezone); $i++) {
			writeln('	<tr>');
			writeln('		<td>' . $k[$i] . '</td>');
			writeln('		<td class="right">' . $timezone[$k[$i]] . '</td>');
			writeln('	</tr>');
		}
		end_tab();

		end_main();
		print_footer();
		die();
	}
}
