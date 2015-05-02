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

include("image.php");

$photo = item_request(TYPE_PHOTO);

if ($auth_zid === "") {
	print_header("Photo");
} else {
	print_header("Photo", array("Share"), array("share"), array(user_link($auth_zid) . "stream/share"));
}
beg_main();

writeln('<h1>Photo</h1>');

$info = photo_info($photo);

$res = array("<a href=\"" . $info["small_link"] . "\">Small ({$info["small_width"]}x{$info["small_height"]})</a>");
if ($photo["has_medium"]) {
	$res[] = "<a href=\"" . $info["medium_link"] . "\">Medium ({$info["medium_width"]}x{$info["medium_height"]})</a>";
}
if ($photo["has_large"]) {
	$res[] = "<a href=\"" . $info["large_link"] . "\">Large ({$info["large_width"]}x{$info["large_height"]})</a>";
}

writeln('<div class="photo-frame">');
writeln('	<img alt="photo" class="' . $info["largest_retina"] . '" src="' . $info["largest_link"] . '">');
writeln('	<div>' . implode(" | ", $res) . '</div>');
writeln('</div>');

dict_beg();
dict_row("Author", user_link($photo["zid"], ["tag" => true]));
dict_row("File", $photo["original_name"]);
dict_row("Time", date("Y-m-d H:i", $photo["time"]));
dict_row("License", '<a href="https://creativecommons.org/licenses/by-sa/4.0/">CC BY-SA 4.0</a>');
dict_end();

end_main();
print_footer();
