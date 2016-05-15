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

include("drive.php");

require_admin();

$spinner[] = ["name" => "Tools", "link" => "/tools/"];
$spinner[] = ["name" => "Cache", "link" => "/tools/cache"];

print_header(["form" => true]);

beg_tab("Delete From Cache");
print_row(array("caption" => "URL or URL Hash", "text_key" => "hash"));
end_tab();
box_right("Delete");

print_footer(["form" => true]);
