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

require_admin();
require_https($https_enabled);

$zones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
$languages = lang_list();

$spinner[] = ["name" => "Tools", "link" => "/tools/"];
$spinner[] = ["name" => "Settings", "link" => "/tools/settings"];

print_header(["form" => true]);

beg_tab("Name");
print_row(array("caption" => "Domain", "text_key" => "server_name", "text_value" => $server_conf["server_name"]));
print_row(array("caption" => "Title", "text_key" => "server_title", "text_value" => $server_conf["server_title"]));
print_row(array("caption" => "Slogan", "text_key" => "server_slogan", "text_value" => $server_conf["server_slogan"]));
print_row(array("caption" => "Redirect to official domain", "check_key" => "server_redirect_enabled", "checked" => $server_conf["server_redirect_enabled"]));
end_tab();

beg_tab("Captcha");
print_row(array("caption" => "TextCaptcha.com Key", "text_key" => "captcha_key", "text_value" => $server_conf["captcha_key"]));
end_tab();

beg_tab("Display");
print_row(array("caption" => "Time Zone", "option_key" => "time_zone", "option_list" => $zones, "option_value" => $server_conf["time_zone"]));
print_row(array("caption" => "Language", "option_key" => "lang", "option_list" => $languages, "option_value" => $server_conf["lang"]));
end_tab();

beg_tab("Features");
print_row(array("caption" => "HTTPS Enabled", "check_key" => "https_enabled", "checked" => $server_conf["https_enabled"]));
print_row(array("caption" => "HTTPS Required", "check_key" => "https_redirect_enabled", "checked" => $server_conf["https_redirect_enabled"]));
print_row(array("caption" => "User Registration", "check_key" => "register_enabled", "checked" => $server_conf["register_enabled"]));
print_row(array("caption" => "Story Submissions", "check_key" => "submit_enabled", "checked" => $server_conf["submit_enabled"]));
print_row(array("caption" => "Bug Tracker", "check_key" => "bug_enabled", "checked" => $server_conf["bug_enabled"]));
print_row(array("caption" => "Access Log", "check_key" => "access_log_enabled", "checked" => $server_conf["access_log_enabled"]));
end_tab();

beg_tab("SMTP");
print_row(array("caption" => "Server Name", "text_key" => "smtp_server", "text_value" => $server_conf["smtp_server"]));
print_row(array("caption" => "Email Address", "text_key" => "smtp_address", "text_value" => $server_conf["smtp_address"]));
print_row(array("caption" => "Username", "text_key" => "smtp_username", "text_value" => $server_conf["smtp_username"]));
print_row(array("caption" => "Password", "password_key" => "smtp_password", "password_value" => $server_conf["smtp_password"]));
end_tab();

beg_tab("Twitter");
print_row(array("caption" => "Enable story tweets", "check_key" => "twitter_enabled", "checked" => $server_conf["twitter_enabled"]));
print_row(array("caption" => "Consumer Key", "text_key" => "twitter_consumer_key", "text_value" => $server_conf["twitter_consumer_key"]));
print_row(array("caption" => "Consumer Secret", "text_key" => "twitter_consumer_secret", "text_value" => $server_conf["twitter_consumer_secret"]));
print_row(array("caption" => "OAuth Token", "text_key" => "twitter_oauth_token", "text_value" => $server_conf["twitter_oauth_token"]));
print_row(array("caption" => "OAuth Secret", "text_key" => "twitter_oauth_secret", "text_value" => $server_conf["twitter_oauth_secret"]));
end_tab();

box_right("Save");

print_footer();
