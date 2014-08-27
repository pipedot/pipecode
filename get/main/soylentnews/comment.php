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

print_header("Import Comment");
beg_main();
beg_form();

writeln('<h1>Import Comment</h1>');

beg_tab();
print_row(array("caption" => "CID", "text_key" => "cid"));
print_row(array("caption" => "SID", "text_key" => "sid"));
end_tab();

right_box("Import");

end_form();
end_main();
print_footer();