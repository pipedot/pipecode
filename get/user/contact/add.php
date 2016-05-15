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

require_mine();

$spinner[] = ["name" => "Contact", "link" => "/contact/"];
$spinner[] = ["name" => "Add", "link" => "/contact/add"];

print_header(["title" => "Add Contact", "form" => true]);

beg_tab();
print_row(["caption" => "Name", "text_key" => "name", "text_value" => ""]);
print_row(["caption" => "Email", "text_key" => "email", "text_value" => ""]);
end_tab();

box_right("Add");

print_footer(["form" => true]);
