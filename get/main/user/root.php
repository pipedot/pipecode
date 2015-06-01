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

$zid = domain_to_zid($s2);
if (!is_local_user($zid)) {
	die("user not found [$zid]");
}
$conf = db_get_conf("user_conf", $zid);

print_header($zid);
beg_main();
beg_form();

writeln('<div class="box">');
writeln('<dl class="dl-32" style="background-image: url(' . avatar_picture($zid, 64) . ')">');
writeln('	<dt><b>' . user_link($zid, ["tag" => true]) . '</b></dt>');
writeln('	<dd style="color: #666666;">joined ' . date("Y-m-d", $conf["joined"]) . '</dd>');
writeln('</dl>');
writeln('</div>');

if ($auth_user["admin"]) {
	beg_tab();
	print_row(array("caption" => "Admin", "check_key" => "admin", "checked" => $conf["admin"]));
	print_row(array("caption" => "Editor", "check_key" => "editor", "checked" => $conf["editor"]));
	end_tab();

	box_right("Save");
} else {
	dict_beg();
	dict_row("Admin", $conf["admin"]);
	dict_row("Editor", $conf["editor"]);
	dict_end();
}

end_form();
end_main();
print_footer();
