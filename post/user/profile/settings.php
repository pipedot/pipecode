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

$zones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

$display_name = http_post_string("display_name", array("len" => 50, "required" => false, "valid" => "[a-z][A-Z]- "));
$birthday = http_post_string("birthday", array("len" => 50, "required" => false, "valid" => "[a-z][A-Z][0-9]-,.: "));
$email = http_post_string("email", array("len" => 50, "valid" => "[a-z][A-Z][0-9]@.-_+"));
$time_zone = http_post_string("time_zone", array("len" => 50, "valid" => "[a-z][A-Z]-_/"));
$large_text_enabled = http_post_bool("large_text_enabled", array("numeric" => true));
$story_image_style = http_post_int("story_image_style");
$hide_threshold = http_post_string("hide_threshold", array("valid" => "[0-9]-"));
$expand_threshold = http_post_string("expand_threshold", array("valid" => "[0-9]-"));
$show_junk_enabled = http_post_bool("show_junk_enabled", array("numeric" => true));
//$gravatar_enabled = http_post_bool("gravatar_enabled", array("numeric" => true));
$javascript_enabled = http_post_bool("javascript_enabled", array("numeric" => true));
$wysiwyg_enabled = http_post_bool("wysiwyg_enabled", array("numeric" => true));
$inline_reply_enabled = http_post_bool("inline_reply_enabled", array("numeric" => true));
$show_name_enabled = http_post_bool("show_name_enabled", array("numeric" => true));
$show_birthday_enabled = http_post_bool("show_birthday_enabled", array("numeric" => true));
$show_email_enabled = http_post_bool("show_email_enabled", array("numeric" => true));
//$list_enabled = http_post_bool("list_enabled", array("numeric" => true));

if (!in_array($time_zone, $zones)) {
	fatal("Invalid time zone");
}
$birthday = strtotime($birthday);
if ($birthday === false) {
	$birthday = 0;
}

$user_conf["display_name"] = $display_name;
$user_conf["birthday"] = $birthday;
$user_conf["email"] = $email;
$user_conf["time_zone"] = $time_zone;
$user_conf["large_text_enabled"] = $large_text_enabled;
$user_conf["story_image_style"] = $story_image_style;
$user_conf["hide_threshold"] = $hide_threshold;
$user_conf["expand_threshold"] = $expand_threshold;
$user_conf["show_junk_enabled"] = $show_junk_enabled;
//$user_conf["gravatar_enabled"] = $gravatar_enabled;
$user_conf["javascript_enabled"] = $javascript_enabled;
$user_conf["wysiwyg_enabled"] = $wysiwyg_enabled;
$user_conf["inline_reply_enabled"] = $inline_reply_enabled;
$user_conf["show_name_enabled"] = $show_name_enabled;
$user_conf["show_birthday_enabled"] = $show_birthday_enabled;
$user_conf["show_email_enabled"] = $show_email_enabled;
//$user_conf["list_enabled"] = $list_enabled;

db_set_conf("user_conf", $user_conf, $auth_zid);

header("Location: /profile/");
