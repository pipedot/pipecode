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

if (!$accounting_enabled) {
	die("accounting disabled");
}
if ($zid != $auth_zid) {
	die("not your page");
}

print_header("Add Funds");
print_left_bar("user", "account");
beg_main("cell");
beg_form();

writeln('<h1>Add Funds</h1>');

beg_tab();
print_row(array("caption" => "Amount", "text_key" => "amount"));
end_tab();

right_box("Add");

end_form();
end_main();
print_footer();

