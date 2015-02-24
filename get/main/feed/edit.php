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

if (!$auth_user["admin"]) {
	die("you are not an admin");
}

$feed = find_rec("feed");
$short_code = crypt_crockford_encode($feed["feed_id"]);

print_header("Edit Feed");
beg_main();
beg_form();

writeln('<h1>Edit Feed</h1>');

dict_beg();
dict_row("Title", $feed["title"]);
dict_row("Link", '<a href="' . $feed["link"] . '">' . $feed["link"] . '</a>');
dict_row("Feed", '<a href="' . $feed["uri"] . '">' . $feed["uri"] . '</a>');
if ($feed["copyright"] != "") {
	dict_row("Copyright", $feed["copyright"]);
}
dict_row("Updated", date("Y-m-d H:i", $feed["time"]));
dict_end();

beg_tab();
print_row(array("caption" => "Slug", "text_key" => "slug", "text_value" => $feed["slug"]));
end_tab();

right_box("Delete,Save");

end_form();
end_main();
print_footer();
