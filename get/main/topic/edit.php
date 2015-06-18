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

if ($s2 === "edit") {
	$topic = db_new_rec("topic");
	$friendly_name = "New Topic";
} else {
	if (!string_uses($s2, "[a-z]")) {
		die("invalid topic");
	}
	$topic = $s2;
	$topic = db_get_rec("topic", array("slug" => $topic));
	$friendly_name = ucwords($topic["topic"]);
}

print_header($friendly_name);
beg_main();
beg_form();

writeln('<h1>' . $friendly_name . '</h1>');

$icons = icon_list(false, true, true);

beg_tab();
print_row(array("caption" => "Name", "text_key" => "name", "text_value" => $topic["topic"]));
print_row(array("caption" => "Slug", "text_key" => "slug", "text_value" => $topic["slug"]));
print_row(array("caption" => "Icon", "option_key" => "icon", "option_list" => $icons, "option_value" => $topic["icon"]));
print_row(array("caption" => "Promoted", "check_key" => "promoted", "checked" => $topic["promoted"]));
end_tab();

if ($s2 === "edit") {
	box_two('<a href="/icons">Icons</a>', "Save");
} else {
	box_two('<a href="/icons">Icons</a>', "Delete,Save");
}

end_form();
end_main();
print_footer();

