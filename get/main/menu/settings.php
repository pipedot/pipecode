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

$themes = array();
$a = fs_dir("$doc_root/www/theme");
for ($i = 0; $i < count($a); $i++) {
	if (is_dir("$doc_root/www/theme/$a[$i]")) {
		$themes[] = $a[$i];
	}
}

print_header("Settings");
beg_main();
beg_form();
writeln('<h1>Settings</h1>');

beg_tab("Appearance");
print_row(array("caption" => "Theme", "option_key" => "theme", "option_list" => $themes, "option_value" => $server_conf["theme"]));
end_tab();

beg_tab("Features");
print_row(array("caption" => "Allow Sign Up", "check_key" => "sign_up_enabled", "checked" => $server_conf["sign_up_enabled"]));
print_row(array("caption" => "Allow Story Submissions", "check_key" => "submit_enabled", "checked" => $server_conf["submit_enabled"]));
end_tab();

right_box("Save");

end_form();
end_main();
print_footer();
