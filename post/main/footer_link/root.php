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

if (!string_uses($s2, "[a-z][0-9]-")) {
	fatal("Invalid slug");
}
$old_slug = $s2;

$name = http_post_string("name", ["valid" => "[A-Z][a-z][0-9]_-. ", "len" => 100]);
$slug = http_post_string("slug", ["valid" => "[a-z][0-9]-", "len" => 100]);
$icon = http_post_string("icon", ["valid" => "[a-z][0-9]-", "len" => 20, "required" => false]);
$link = http_post_string("link", ["valid" => "[a-z][A-Z][0-9]~@#$%&()-_=+[];:,./?", "len" => 200]);

$footer_link = db_get_rec("footer_link", $old_slug);
$footer_link["slug"] = $slug;
$footer_link["name"] = $name;
$footer_link["icon"] = $icon;
$footer_link["link"] = $link;
db_set_rec("footer_link", $footer_link);

header("Location: ./");

