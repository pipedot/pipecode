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

$key = $s2;
if (!string_uses($key, "[0-9]abcdef") || strlen($key) != 64) {
	fatal("Invalid key");
}
$tiny_key = substr($key, 0, 8);

print_header("Login", [], [], [], ["Login", $tiny_key], ["/login/", "/login/$key"]);
beg_main();
beg_form();

beg_tab();
$row = sql("select agent_id, os_id, last_time, country_name, address, latitude, longitude from login inner join ip on login.ip_id = ip.ip_id inner join country on ip.country_id = country.country_id where zid = ? and login_key = ?", $zid, $key);
if (count($row) == 0) {
	fatal("Login key not found");
}

if ($row[0]["latitude"] != 0 && $row[0]["longitude"] != 0) {
	writeln('<div class="outline center">');
	writeln('<img alt="map" class="map" src="map?lat=' . $row[0]["latitude"] . '&lon=' . $row[0]["longitude"] . '">');
	writeln('</div>');
}

dict_beg();
dict_row("User Agent", item_type($row[0]["agent_id"]));
dict_row("Operating System", item_type($row[0]["os_id"]));
dict_row("IP Address", $row[0]["address"]);
if ($row[0]["country_name"] != "") {
	dict_row("Country", $row[0]["country_name"]);
}
//dict_row("Last Access", human_diff($now - $row[0]["last_time"]));
dict_row("Last Access", date("Y-m-d H:i", $row[0]["last_time"]));
dict_end();

box_right("Delete");

end_form();
end_main();
print_footer();


