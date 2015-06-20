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
	$topic = db_new_rec("reader_topic");
	$title = "New Topic";
} else {
	if (!string_uses($s2, "[a-z]")) {
		fatal("Invalid topic");
	}
	$slug = $s2;
	$topic = db_get_rec("reader_topic", array("slug" => $slug));
	$title = $topic["name"];
}

print_header($title);
beg_main();
beg_form();

writeln('<h1>' . $title . '</h1>');

$data = fs_slurp("$doc_root/www/style.css");
preg_match_all("/\.([a-z-]+)-64 {/", $data, $out);
$icons = array();
for ($i = 0; $i < count($out[1]); $i++) {
	$icon = $out[1][$i];
	if ($icon != "icon") {
		$icons[$icon] = true;
	}
}
$icons = array_keys($icons);

beg_tab();
print_row(array("caption" => "Name", "text_key" => "name", "text_value" => $topic["name"]));
print_row(array("caption" => "Slug", "text_key" => "slug", "text_value" => $topic["slug"]));
print_row(array("caption" => "Icon", "option_key" => "icon", "option_list" => $icons, "option_value" => $topic["icon"]));
end_tab();

if ($s2 === "edit") {
	box_right("Save");
} else {
	box_right("Delete,Save");
}

end_form();
end_main();
print_footer();


