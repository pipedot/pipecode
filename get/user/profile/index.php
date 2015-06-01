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

include("gravatar.php");

print_header("Profile", [], [], [], ["Profile"], ["/profile/"]);
beg_main("dual-table");
writeln('<div class="dual-left">');

dict_beg("Information");
if ($user_conf["show_name_enabled"] && $user_conf["display_name"] != "") {
	dict_row('<span class="icon-16 user-16">Name</span>', $user_conf["display_name"]);
}
if ($user_conf["show_birthday_enabled"] && $user_conf["birthday"] != 0) {
	dict_row('<span class="icon-16 cake-16">Birthday</span>', gmdate("F j", $user_conf["birthday"]));
}
if ($user_conf["show_email_enabled"] && $user_conf["email"] != "") {
	dict_row('<span class="icon-16 mail-16">Email</span>', '<a href="mailto:' . $user_conf["email"] . '">' . $user_conf["email"] . '</a>');
}
if ($user_conf["joined"] != 0) {
	dict_row('<span class="icon-16 calendar-16">Joined</span>', date("Y-m-d", $user_conf["joined"]));
}
dict_end();

if ($zid === $auth_zid) {
	box_right('<a class="icon-16 tools-16" href="settings">Settings</a>');
}

writeln('</div>');
writeln('<div class="dual-right">');

beg_tab("Avatar");
writeln('	<tr>');
writeln('		<td class="center"><a href="/avatar/"><img alt="Avatar" class="thumb" src="' . avatar_picture($zid, 256) . '"></a></td>');
writeln('	</tr>');
writeln('</table>');
seen_gravatar($zid);

//if ($zid === $auth_zid) {
//	box_right('<a class="icon-16 picture-16" href="/avatar/">Change</a>');
//}

writeln('</div>');
end_main();
print_footer();
