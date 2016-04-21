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

require_feature("bug");
require_login();

$spinner[] = ["name" => "Bug", "link" => "/bug/"];
$spinner[] = ["name" => "Attach File", "link" => "/bug/attach"];
$actions[] = ["name" => "Report", "icon" => "ladybug", "link" => "/bug/report"];

print_header();
beg_form("", "file");

writeln('<h1>Attach File</h1>');

box_two('<input name="upload" type="file">', "Upload");

end_form();
print_footer();
