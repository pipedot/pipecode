<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
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

$photo = find_rec("photo");

if ($auth_zid === "") {
	print_header("Photo");
} else {
	print_header("Photo", array("Share"), array("share"), array(user_page_link($auth_zid) . "stream/share"));
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

writeln('<div class="photo_frame">');
writeln('	<img alt="photo" class="' . $info["largest_retina"] . '" src="' . $info["largest_link"] . '"/>');
writeln('	<div>' . implode(" | ", $res) . '</div>');
writeln('</div>');

beg_tab();
writeln('	<tr>');
writeln('		<td>Author</td>');
writeln('		<td class="right">' . user_page_link($photo["zid"], true) . '</td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td>File</td>');
writeln('		<td class="right">' . $photo["original_name"] . '</td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td>Time</td>');
writeln('		<td class="right">' . date("Y-m-d H:i", $photo["time"]) . '</td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td>License</td>');
writeln('		<td class="right"><a href="https://creativecommons.org/licenses/by-sa/4.0/">CC BY-SA 4.0</a></td>');
writeln('	</tr>');
end_tab();

end_main();
print_footer();
