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

$image_id = (int) $s2;
$image = db_get_rec("image", $image_id);

if ($auth_zid == "") {
	print_header("Image");
} else {
	print_header("Image", array("Share"), array("notepad"), array(user_page_link($auth_zid) . "stream/share"));
}
beg_main();

writeln('<h1>Image</h1>');

$width = 320;
if ($image["aspect_width"] == 1 && $image["aspect_height"] == 1) {
	$height = 320;
} else if ($image["aspect_width"] == 4 && $image["aspect_height"] == 3) {
	$height = 240;
} else if ($image["aspect_width"] == 16 && $image["aspect_height"] == 9) {
	$height = 180;
}
if ($image["has_640"]) {
	$width *= 2;
	$height *= 2;
}
if ($image["has_1280"]) {
	$width *= 2;
	$height *= 2;
}
$path = public_path($image["time"]) . "/i$image_id.{$width}x{$height}.jpg";
$image = "$protocol://$server_name$path?" . fs_time("$doc_root$path");

writeln('<table style="border: 1px #d3d3d3 solid; margin-bottom: 8px;">');
writeln('	<tr>');
writeln('		<td style="background-color: #eeeeee; padding: 8px;"><img alt="photo" src="' . $image . '"/></td>');
writeln('	</tr>');
writeln('</table>');

end_main();
print_footer();
