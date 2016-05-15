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
$name = http_post_string("name", array("valid" => "[A-Z][a-z][0-9]-. ", "len" => 50));
$slug = http_post_string("slug", array("valid" => "[a-z][0-9]-", "len" => 50));

$reader_topic = db_new_rec("reader_topic");
$reader_topic["icon"] = $icon;
$reader_topic["name"] = $name;
$reader_topic["slug"] = $slug;
$reader_topic["zid"] = $auth_zid;
db_set_rec("reader_topic", $reader_topic);

header("Location: /reader/topic/");

