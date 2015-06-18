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

$server_name = http_post_string("server_name", array("len" => 50, "valid" => "[a-z][0-9]-."));
$server_title = http_post_string("server_title", array("len" => 50, "valid" => "[KEYBOARD]"));
$server_slogan = http_post_string("server_slogan", array("len" => 50, "valid" => "[KEYBOARD]"));
$server_redirect_enabled = http_post_bool("server_redirect_enabled", array("numeric" => true));

$captcha_key = http_post_string("captcha_key", array("len" => 100, "required" => false, "valid" => "[KEYBOARD]"));

$time_zone = http_post_string("time_zone", array("len" => 50, "valid" => "[a-z][A-Z]-_/"));

$https_enabled = http_post_bool("https_enabled", array("numeric" => true));
$register_enabled = http_post_bool("register_enabled", array("numeric" => true));
$submit_enabled = http_post_bool("submit_enabled", array("numeric" => true));
$bug_enabled = http_post_bool("bug_enabled", array("numeric" => true));

$twitter_enabled = http_post_bool("twitter_enabled", array("numeric" => true));
$twitter_consumer_key = http_post_string("twitter_consumer_key", array("len" => 100, "required" => false, "valid" => "[KEYBOARD]"));
$twitter_consumer_secret = http_post_string("twitter_consumer_secret", array("len" => 100, "required" => false, "valid" => "[KEYBOARD]"));
$twitter_oauth_token = http_post_string("twitter_oauth_token", array("len" => 100, "required" => false, "valid" => "[KEYBOARD]"));
$twitter_oauth_secret = http_post_string("twitter_oauth_secret", array("len" => 100, "required" => false, "valid" => "[KEYBOARD]"));

$server_conf["server_name"] = $server_name;
$server_conf["server_title"] = $server_title;
$server_conf["server_slogan"] = $server_slogan;
$server_conf["server_redirect_enabled"] = $server_redirect_enabled;
$server_conf["captcha_key"] = $captcha_key;
$server_conf["time_zone"] = $time_zone;
$server_conf["https_enabled"] = $https_enabled;
$server_conf["register_enabled"] = $register_enabled;
$server_conf["submit_enabled"] = $submit_enabled;
$server_conf["bug_enabled"] = $bug_enabled;
$server_conf["twitter_enabled"] = $twitter_enabled;
$server_conf["twitter_consumer_key"] = $twitter_consumer_key;
$server_conf["twitter_consumer_secret"] = $twitter_consumer_secret;
$server_conf["twitter_oauth_token"] = $twitter_oauth_token;
$server_conf["twitter_oauth_secret"] = $twitter_oauth_secret;
db_set_conf("server_conf", $server_conf);

header("Location: /menu/");
