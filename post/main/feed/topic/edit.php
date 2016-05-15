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

require_admin();

$icon = http_post_string("icon", array("valid" => "[a-z][0-9]-", "len" => 20));
$name = http_post_string("name", array("valid" => "[A-Z][a-z][0-9]`~!@#$%^&*()_+-=[]\{}|;':\",./? ", "len" => 50));
$slug = http_post_string("slug", array("valid" => "[a-z][0-9]-", "len" => 50));

if (!string_uses($s3, "[a-z]")) {
	fatal("Invalid topic");
}
$feed_topic = db_get_rec("feed_topic", ["slug" => $s3]);
$feed_topic["icon"] = $icon;
$feed_topic["name"] = $name;
$feed_topic["slug"] = $slug;
db_set_rec("feed_topic", $feed_topic);

header("Location: /feed/topic/list");
