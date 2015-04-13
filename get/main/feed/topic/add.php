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

$icons = icon_list(true, true, true);

print_header("New Topic");
beg_main();
beg_form();
writeln('<h1>New Topic</h1>');

beg_tab();
print_row(array("caption" => "Name", "text_key" => "name"));
print_row(array("caption" => "Slug", "text_key" => "slug"));
print_row(array("caption" => "Icon", "option_key" => "icon", "option_list" => $icons));
end_tab();

box_right("Save");

end_form();
end_main();
print_footer();
