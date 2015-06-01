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

if ($zid !== $auth_zid) {
	die("not your page");
}

$avatar = item_request(TYPE_AVATAR);
$avatar_code = $avatar["short_code"];

print_header("Delete Avatar", [], [], [], ["Avatar", $avatar_code, "Delete"], ["/avatar/", "/avatar/$avatar_code", "/activate/$avatar_code/delete"]);
beg_main();
beg_form();

writeln("<h2>Delete this avatar?</h2>");
writeln('<div class="box"><img alt="avatar" class="thumb" src="' . $protocol . '://' . $server_name . '/avatar/' . $avatar_code . '-256.jpg"></div>');

box_left("Delete");

end_form();
end_main();
print_footer();

