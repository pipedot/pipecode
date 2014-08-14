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

include("image.php");

if ($zid != $auth_zid) {
	die("not your page");
}

print_header("Profile");
beg_main();
beg_form("", "file");

beg_tab("Profile Picture", array("colspan" => 2));
writeln('	<tr>');
writeln('		<td colspan="2"><img style="width: 128px" src="/pub/profile/' . $server_name . "/" . $user_page . '-256.jpg?' . fs_time("$doc_root/www/pub/profile/$server_name/$user_page-256.jpg") . '"/></td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td style="width: 100%"><input name="upload" type="file" style="width: 100%"/></td>');
writeln('		<td style="width: 50px"><input type="submit" value="Upload"/></td>');
writeln('	</tr>');
end_tab();

end_form();
end_main();
print_footer();
