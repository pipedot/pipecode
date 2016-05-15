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

if (!string_uses($s2, "[a-z][0-9]-", 100)) {
	fatal("Invalid slug");
}
$slug = $s2;

$footer_link = db_get_rec("footer_link", $slug);
$icons = icon_list(true, false, false, true);

$spinner[] = ["name" => "Footer Link", "link" => "/footer_link/"];
$spinner[] = ["name" => $footer_link["name"], "link" => "/footer_link/$slug"];

print_header(["form" => true]);

beg_tab();
print_row(["caption" => "Name", "text_key" => "name", "text_value" => $footer_link["name"]]);
print_row(["caption" => "Slug", "text_key" => "slug", "text_value" => $slug]);
print_row(["caption" => "Icon", "option_key" => "icon", "option_list" => $icons, "option_value" => $footer_link["icon"]]);
print_row(["caption" => "Link", "text_key" => "link", "text_value" => $footer_link["link"]]);
end_tab();

box_right("Save");

print_footer();
