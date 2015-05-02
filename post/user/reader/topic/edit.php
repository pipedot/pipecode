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

if ($zid !== $auth_zid) {
	die("not your page");
}

$reader_topic = item_request(TYPE_READER_TOPIC);

$icon = http_post_string("icon", array("valid" => "[a-z]-", "len" => 20));
$name = http_post_string("name", array("valid" => "[A-Z][a-z][0-9]`~!@#$%^&*()_+-=[]\{}|;':\",./? ", "len" => 50));
$slug = http_post_string("slug", array("valid" => "[a-z][0-9]-", "len" => 50));

$reader_topic["icon"] = $icon;
$reader_topic["name"] = $name;
$reader_topic["slug"] = $slug;
db_set_rec("reader_topic", $reader_topic);

header("Location: /reader/topic/");

