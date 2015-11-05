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

$title = http_get_string("title", ["len" => 100, "valid" => "[A-Z][a-z][0-9]_-. "]);

$footer_link = db_get_rec("footer_link", $title);

print_header("Remove Footer Link");
beg_main();
beg_form();

writeln('<h1>' . get_text('Remove Footer Link') . '</h1>');
writeln('<p>' . get_text('Are you sure you want to remove the [<b>$1</b>] link?', $title) . '</p>');

box_left("Remove");

end_form();
end_main();
print_footer();
