<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
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

include("file.php");

if (!$auth_user["editor"] && !$auth_user["admin"]) {
	die("not an editor or an admin");
}

$bug_file = find_rec("bug_file");

print_header("Delete File", array("Report"), array("ladybug"), array("/bug/report"));
beg_main();
beg_form();

writeln('<h1>Delete File</h1>');

writeln('<p>Are you sure you want to delete this file?</p>');

beg_tab();
writeln('<tr><td><a class="icon_16 ' . file_icon($bug_file["type"]) . '" href="/pub/bug/' . $bug_file["short_code"] . '.' . $bug_file["type"] . '">' . $bug_file["name"] . '</a></td></tr>');
end_tab();

left_box("Delete");

end_form();
end_main();
print_footer();

