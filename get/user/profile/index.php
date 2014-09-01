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

print_header("Profile Settings");
beg_main();
beg_form();
writeln('<h1>Profile Settings</h1>');

beg_tab("JavaScript");
print_row(array("caption" => "Enable JavaScript", "check_key" => "javascript_enabled", "checked" => $user_conf["javascript_enabled"]));
print_row(array("caption" => "WYSIWYG Editor", "check_key" => "wysiwyg_enabled", "checked" => $user_conf["wysiwyg_enabled"]));
end_tab();

beg_tab("Display");
$row = sql("select image_style_id, description from image_style order by image_style_id");
$image_styles = array();
$image_descriptions = array();
for ($i = 0; $i < count($row); $i++) {
	$image_styles[] = $row[$i]["image_style_id"];
	$image_descriptions[] = $row[$i]["description"];
}
print_row(array("caption" => "Story Image Style", "option_key" => "story_image_style", "option_keys" => $image_styles, "option_list" => $image_descriptions, "option_value" => $user_conf["story_image_style"]));
print_row(array("caption" => "Time Zone", "option_key" => "time_zone", "option_list" => $zones, "option_value" => $user_conf["time_zone"]));
//print_row(array("caption" => "Show posts from SoylentNews", "check_key" => "soylentnews_enabled", "checked" => $user_conf["soylentnews_enabled"]));
end_tab();

beg_tab("Comments");
$scores = array("-1", "0", "1", "2", "3", "4", "5");
print_row(array("caption" => "Hide Threshold", "option_key" => "hide_threshold", "option_list" => $scores, "option_value" => $user_conf["hide_threshold"]));
print_row(array("caption" => "Expand Threshold", "option_key" => "expand_threshold", "option_list" => $scores, "option_value" => $user_conf["expand_threshold"]));
end_tab();

beg_tab("Profile");
print_row(array("caption" => "Real Name", "text_key" => "real_name", "text_value" => $user_conf["real_name"]));
print_row(array("caption" => "External Email", "text_key" => "email", "text_value" => $user_conf["email"]));
end_tab();

beg_tab("Mailing List");
print_row(array("caption" => "Subscribe to Mailing List (list@$server_name)", "check_key" => "list_enabled", "checked" => $user_conf["list_enabled"]));
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

end_form();
end_main();
print_footer();
