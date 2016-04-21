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

require_mine();

$auth = @$_COOKIE["auth"];
$map = map_from_url_string($auth);
$current_key = @$map["key"];

$spinner[] = ["name" => "Login", "link" => "/login/"];

print_header(["title" => "Active Sessions", "form" => true]);

beg_tab();
$row = sql("select login_key, agent_id, os_id, last_time, country_name, address from login inner join ip on login.ip_id = ip.ip_id inner join country on ip.country_id = country.country_id where zid = ?", $zid);
for ($i = 0; $i < count($row); $i++) {
	$agent = item_type($row[$i]["agent_id"]);
	$os = item_type($row[$i]["os_id"]);
	if ($row[$i]["country_name"] == "") {
		$country = "";
	} else {
		$country = " (" . $row[$i]["country_name"] . ")";
	}
	$ip = $row[$i]["address"];
	if ($row[$i]["login_key"] == $current_key) {
		$last_time = ' (' . get_text('current session') . ')';
	} else {
		$diff = human_diff($now - $row[$i]["last_time"]);
		$last_time = ' (' . get_text('$1 ago', $diff) . ')';
	}
	$icon = strtolower($agent);
	if ($icon == "pipecode server" || $icon == "pipedot app") {
		$icon = "pipedot";
	} else if ($icon == "pale moon") {
		$icon = "palemoon";
	} else if ($icon == "internet explorer") {
		$icon = "ie";
	} else if ($icon == "unknown") {
		$agent = get_text('Unknown Browser');
		$icon = "globe";
	}

	writeln('	<tr>');
	writeln('		<td class="hover">');
	writeln('			<a href="' . $row[$i]["login_key"] . '">');
	writeln('			<dl class="dl-32 ' . $icon . '-32">');
	writeln('				<dt>' . $agent . $last_time . '</dt>');
	writeln('				<dd>' . $os . ', ' . $ip . $country . '</dd>');
	writeln('			<dl>');
	writeln('			</a>');
	writeln('		</td>');
	writeln('	</tr>');
}
end_tab();

box_right("Delete All");

print_footer(["form" => true]);
