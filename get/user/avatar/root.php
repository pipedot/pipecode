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

include("drive.php");
include("avatar.php");

$avatar = item_request(TYPE_AVATAR);
if ($avatar["zid"] != $zid) {
	die("avatar not set by this user");
}
$avatar_code = $avatar["short_code"];
$avatar_root = "$protocol://$server_name/avatar/$avatar_code";

print_header("Avatar", [], [], [], ["Avatar", $avatar_code], ["/avatar/", "/avatar/$avatar_code"]);
beg_main();

writeln('<h1>Avatar</h1>');
writeln('<div class="photo-frame">');
writeln('	<img alt="avatar" class="thumb" src="' . $avatar_root . '-256.jpg">');
writeln('	<div><a href="' . $avatar_root . '-64.png">Small (64x64)</a> | <a href="' . $avatar_root . '-128.jpg">Medium (128x128)</a> | <a href="' . $avatar_root . '-256.jpg">Large (256x256)</a></div>');
writeln('</div>');

dict_beg();
dict_row("Author", user_link($avatar["zid"], ["tag" => true]));
dict_row("Time", date("Y-m-d H:i", $avatar["time"]));
dict_row("License", '<a href="https://creativecommons.org/licenses/by-sa/4.0/">CC BY-SA 4.0</a>');
dict_end();

end_main();
print_footer();
