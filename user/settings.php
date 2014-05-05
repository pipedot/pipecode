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

if ($zid != $auth_zid) {
	die("not your page");
}

$zones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

if (http_post()) {
	$javascript_enabled = http_post_bool("javascript_enabled", array("numeric" => true));
	$time_zone = http_post_string("time_zone", array("len" => 50, "valid" => "[a-z][A-Z]-_/"));
	$hide_threshold = http_post_string("hide_threshold", array("valid" => "[0-9]-"));
	$expand_threshold = http_post_string("expand_threshold", array("valid" => "[0-9]-"));
	$list_enabled = http_post_bool("list_enabled", array("numeric" => true));
	$real_name = http_post_string("real_name", array("len" => 50, "required" => false, "valid" => "[a-z][A-Z]- "));

	if (!in_array($time_zone, $zones)) {
		die("invalid time zone [$time_zone]");
	}

	$user_conf["javascript_enabled"] = $javascript_enabled;
	$user_conf["time_zone"] = $time_zone;
	$user_conf["hide_threshold"] = $hide_threshold;
	$user_conf["expand_threshold"] = $expand_threshold;
	$user_conf["list_enabled"] = $list_enabled;
	$user_conf["real_name"] = $real_name;

	db_set_conf("user_conf", $user_conf, $auth_zid);
	//var_dump($user_conf);
	//die();
	header("Location: /menu/");
	die();
}

print_header("Settings");

writeln('<table class="fill">');
writeln('<tr>');
writeln('<td class="left_col">');
print_left_bar("account", "settings");
writeln('</td>');
writeln('<td class="fill">');

writeln('<h1>Settings</h1>');

writeln('<form method="post">');
beg_tab("JavaScript");
print_row(array("caption" => "Enable JavaScript", "check_key" => "javascript_enabled", "checked" => $user_conf["javascript_enabled"]));
end_tab();

beg_tab("Date and Time");
print_row(array("caption" => "Time Zone", "option_key" => "time_zone", "option_list" => $zones, "option_value" => $user_conf["time_zone"]));
end_tab();

beg_tab("Comments");
$scores = array("-1", "0", "1", "2", "3", "4", "5");
print_row(array("caption" => "Hide Threshold", "option_key" => "hide_threshold", "option_list" => $scores, "option_value" => $user_conf["hide_threshold"]));
print_row(array("caption" => "Expand Threshold", "option_key" => "expand_threshold", "option_list" => $scores, "option_value" => $user_conf["expand_threshold"]));
end_tab();

beg_tab("Mailing List");
print_row(array("caption" => "Subscribe to Mailing List (list@$server_name)", "check_key" => "list_enabled", "checked" => $user_conf["list_enabled"]));
print_row(array("caption" => "Real Name", "text_key" => "real_name", "text_value" => $user_conf["real_name"]));
end_tab();

//
// Eastern: America/New_York
// Central: America/Chicago
// Mountain: America/Denver
// Pacific: America/Los_Angeles
// British Summer Time: London
// Central Europe Time: Paris
// Eastern Europe Time: Athens
//

right_box("Save");
writeln('</form>');

writeln('</td>');
writeln('</tr>');
writeln('</table>');

print_footer();
