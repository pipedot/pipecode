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

print_header("Profile", [], [], [], ["Profile", "Picture"], ["/profile/", "/profile/picture"]);
beg_main();
if ($zid === $auth_zid) {
	beg_form("", "file");
}

beg_tab("Current");
writeln('	<tr>');
writeln('		<td class="center"><img alt="Current Picture" class="thumb" src="' . profile_picture($zid, 256) . '"></td>');
writeln('	</tr>');
end_tab();

if ($zid === $auth_zid) {
	/*
	//beg_tab();
	dict_beg("Previous");
	//writeln('	<tr>');
	//writeln('		<td><img class="avatar-32" src="http://pipedot.net/pub/profile/pipedot.net/bryan-64.png"></td>');
	//writeln('		<td width="15%"><a class="icon-16 delete-16" href="delete">Delete</a></td>');
	//writeln('		<td width="15%"><a class="icon-16 check-16" href="select">Select</a></td>');
	//writeln('	</tr>');
	dict_row('<img alt="Profile Picture" class="avatar-32" src="http://pipedot.net/pub/profile/pipedot.net/bryan-64.png">', '<a class="icon-16 check-16" href="activate">Activate</a> | <a class="icon-16 delete-16" href="delete">Delete</a>');
	dict_row('<img alt="Profile Picture" class="avatar-32" src="http://pipedot.net/pub/profile/pipedot.net/bryan-64.png">', '<a class="icon-16 check-16" href="activate">Activate</a> | <a class="icon-16 delete-16" href="delete">Delete</a>');
	dict_row('<img alt="Profile Picture" class="avatar-32" src="http://pipedot.net/pub/profile/pipedot.net/bryan-64.png">', '<a class="icon-16 check-16" href="activate">Activate</a> | <a class="icon-16 delete-16" href="delete">Delete</a>');
	//dict_row('<img alt="Profile Picture" class="avatar-32" src="http://pipedot.net/pub/profile/pipedot.net/bryan-64.png">', '<input type="submit" name="select_1234" value="Select"> <input type="submit" name="delete_1234" value="Delete">');
	//dict_row('<img alt="Profile Picture" class="avatar-32" src="http://pipedot.net/pub/profile/pipedot.net/bryan-64.png">', '<input type="submit" name="select_1234" value="Select"> <input type="submit" name="delete_1234" value="Delete">');
	//dict_row('<img alt="Profile Picture" class="avatar-32" src="http://pipedot.net/pub/profile/pipedot.net/bryan-64.png">', '<input type="submit" name="select_1234" value="Select"> <input type="submit" name="delete_1234" value="Delete">');
	//end_tab();
	dict_end();
	*/

	box_two('<input name="upload" type="file" style="width: 100%">', 'Upload');
	end_form();

	//beg_tab("Gravatar");
	//print_row(array("caption" => "Use Gravatar (" . $user_conf["email"] . ")", "check_key" => "gravatar_enabled", "checked" => $user_conf["gravatar_enabled"]));
	//end_tab();

	beg_tab("Gravatar");
	writeln('	<tr>');
	writeln('		<td class="center"><img alt="Gravatar Picture" class="thumb" src="' . $protocol . '://www.gravatar.com/avatar/' . md5($user_conf["email"]) . '.jpg?d=mm&s=256"></td>');
	writeln('	</tr>');
	end_tab();

	box_right("Update");
}

end_main();
print_footer();
