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

if (!$auth_user["admin"]) {
	die("not an admin");
}

if (!string_uses($s3, "[a-z][0-9]-")) {
	die("invalid topic");
}
$topic = db_get_rec("feed_topic", array("slug" => $s3));
$icons = icon_list(true, true, true);

print_header($topic["name"]);
beg_main();
beg_form();
writeln('<h1>' . $topic["name"] . '</h1>');

beg_tab();
print_row(array("caption" => "Name", "text_key" => "name", "text_value" => $topic["name"]));
print_row(array("caption" => "Slug", "text_key" => "slug", "text_value" => $topic["slug"]));
print_row(array("caption" => "Icon", "option_key" => "icon", "option_list" => $icons, "option_value" => $topic["icon"]));
end_tab();

box_right("Save");

end_form();
end_main();
print_footer();
