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

print_header("Avatar", [], [], [], ["Avatar"], ["/avatar/"]);
beg_main();

$current_code = crypt_crockford_encode($user_conf["avatar_id"]);
$current_root = "$protocol://$server_name/avatar/$current_code";
beg_tab("Current");
writeln('	<tr>');
writeln('		<td class="center"><a href="' . $current_code . '"><img alt="avatar" class="thumb" src="' . $current_root . '-256.jpg"></a></td>');
writeln('	</tr>');
end_tab();

if ($zid === $auth_zid) {

	dict_beg("Previous");

	$row = sql("select avatar_id from avatar where zid = ? order by time", $auth_zid);
	for ($i = 0; $i < count($row); $i++) {
		$avatar_code = crypt_crockford_encode($row[$i]["avatar_id"]);
		if ($row[$i]["avatar_id"] == $user_conf["avatar_id"]) {
			$links = '<a class="icon-16 delete-16" href="' . $avatar_code . '/delete">Delete</a>';
		} else {
			$links = '<a class="icon-16 check-16" href="' . $avatar_code . '/activate">Activate</a> | <a class="icon-16 delete-16" href="' . $avatar_code . '/delete">Delete</a>';
		}
		dict_row('<a href="' . $avatar_code . '"><img alt="avatar" class="avatar-32" src="' . $protocol . '://' . $server_name . '/avatar/' . $avatar_code . '-64.png"></a>', $links);
	}
	dict_end();

	beg_form("", "file");
	beg_tab("New");
	writeln('	<tr>');
	writeln('		<td><input name="upload" type="file" style="width: 100%"></td>');
	writeln('	</tr>');
	end_tab();
	box_right("Upload");
	end_form();

	beg_form("gravatar");
	beg_tab("Gravatar");
	writeln('	<tr>');
	writeln('		<td class="center"><img alt="Gravatar Picture" class="thumb" src="' . $protocol . '://www.gravatar.com/avatar/' . md5($user_conf["email"]) . '.jpg?d=mm&s=256"></td>');
	writeln('	</tr>');
	end_tab();

	box_right("Import");
	end_form();
}

end_main();
print_footer();
