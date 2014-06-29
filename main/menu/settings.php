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

if (http_post()) {
	$theme = http_post_string("theme", array("len" => 50, "valid" => "[a-z][0-9]-_"));

	$server_conf["theme"] = $theme;

	db_set_conf("server_conf", $server_conf);
	header("Location: /menu/");
	die();
}

$themes = array();
$a = fs_dir("$doc_root/www/theme");
for ($i = 0; $i < count($a); $i++) {
	if (is_dir("$doc_root/www/theme/$a[$i]")) {
		$themes[] = $a[$i];
	}
}

print_header("Settings");

writeln('<h1>Settings</h1>');

beg_form();

beg_tab("Appearance");
print_row(array("caption" => "Theme", "option_key" => "theme", "option_list" => $themes, "option_value" => $server_conf["theme"]));
end_tab();

right_box("Save");
end_form();

print_footer();
