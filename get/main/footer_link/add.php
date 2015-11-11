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

$icons = icon_list(true, false, false, true);

print_header("Add Footer Link");
beg_main();
beg_form();
writeln('<h1>' . get_text("Add Footer Link") . '</h1>');

beg_tab();
print_row(["caption" => "Title", "text_key" => "title"]);
print_row(["caption" => "Icon", "option_key" => "icon", "option_list" => $icons]);
print_row(["caption" => "Link", "text_key" => "link"]);
end_tab();

box_right("Add");

end_form();
end_main();
print_footer();

