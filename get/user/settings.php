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

require_mine();

$zones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
$languages = lang_list();

$spinner[] = ["name" => "Settings", "link" => "/settings"];

print_header(["form" => true]);

beg_tab("Information");
print_row(array("caption" => "Display Name", "text_key" => "display_name", "text_value" => $user_conf["display_name"]));
print_row(array("caption" => "Birthday", "text_key" => "birthday", "text_value" => ($user_conf["birthday"] == 0 ? "" : gmdate("Y-m-d", $user_conf["birthday"]))));
print_row(array("caption" => "External Email", "text_key" => "email", "text_value" => $user_conf["email"]));
end_tab();

beg_tab("Display");
print_row(array("caption" => "Story Image Style", "option_key" => "story_image_style", "option_keys" => array_keys($story_image_style), "option_list" => array_values($story_image_style), "option_value" => $user_conf["story_image_style"]));
print_row(array("caption" => "Time Zone", "option_key" => "time_zone", "option_list" => $zones, "option_value" => $user_conf["time_zone"]));
print_row(array("caption" => "Language", "option_key" => "lang", "option_list" => $languages, "option_value" => $user_conf["lang"]));
print_row(array("caption" => "Large Text", "check_key" => "large_text_enabled", "checked" => $user_conf["large_text_enabled"]));
end_tab();

beg_tab("Comments");
$scores = array("-1", "0", "1", "2", "3", "4", "5");
print_row(array("caption" => "Hide Threshold", "option_key" => "hide_threshold", "option_list" => $scores, "option_value" => $user_conf["hide_threshold"]));
print_row(array("caption" => "Expand Threshold", "option_key" => "expand_threshold", "option_list" => $scores, "option_value" => $user_conf["expand_threshold"]));
print_row(array("caption" => "Show Junk Comments", "check_key" => "show_junk_enabled", "checked" => $user_conf["show_junk_enabled"]));
end_tab();

beg_tab("Features");
print_row(array("caption" => "Enable JavaScript", "check_key" => "javascript_enabled", "checked" => $user_conf["javascript_enabled"]));
print_row(array("caption" => "WYSIWYG Editor", "check_key" => "wysiwyg_enabled", "checked" => $user_conf["wysiwyg_enabled"]));
print_row(array("caption" => "Inline Reply", "check_key" => "inline_reply_enabled", "checked" => $user_conf["inline_reply_enabled"]));
end_tab();

beg_tab("Privacy");
print_row(array("caption" => "Show Name", "check_key" => "show_name_enabled", "checked" => $user_conf["show_name_enabled"]));
print_row(array("caption" => "Show Birthday", "check_key" => "show_birthday_enabled", "checked" => $user_conf["show_birthday_enabled"]));
print_row(array("caption" => "Show Email", "check_key" => "show_email_enabled", "checked" => $user_conf["show_email_enabled"]));
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

box_right("Save");

print_footer(["form" => true]);
