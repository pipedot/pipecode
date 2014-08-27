<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
//
// This file is part of Pipecode.
//
// Pipecode is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Pipecode is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Pipecode.  If not, see <http://www.gnu.org/licenses/>.
//

if (!$auth_user["admin"]) {
	die("not an admin");
}

print_header("Update Comments");
beg_main();
beg_form();

writeln('<h1>Update Comments</h1>');

beg_tab();
print_row(array("caption" => "Start Item", "text_key" => "start", "text_value" => "0"));
print_row(array("caption" => "Max Items", "text_key" => "max_items", "text_value" => "-1"));
print_row(array("caption" => "Max Pages", "text_key" => "max_pages", "text_value" => "-1"));
print_row(array("caption" => "Force", "check_key" => "force"));
end_tab();

right_box("Import");

end_form();
end_main();
print_footer();

