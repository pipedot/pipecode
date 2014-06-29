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

$photo_id = (int) $s2;
$photo = db_get_rec("photo", $photo_id);

if ($auth_zid == "") {
	print_header("Photo");
} else {
	print_header("Photo", array("Share"), array("notepad"), array(user_page_link($auth_zid) . "stream/share"));
}
beg_main();

writeln('<h1>Photo</h1>');

$width = 320;
if ($photo["aspect_width"] == 9 && $photo["aspect_height"] == 16) {
	$width = 320;
	$height = 569;
	if ($photo["has_medium"]) {
		$width = 640;
		$height = 1138;
	}
	if ($photo["has_large"]) {
		$width = 1080;
		$height = 1920;
	}
} else if ($photo["aspect_width"] == 3 && $photo["aspect_height"] == 4) {
	$width = 320;
	$height = 427;
	if ($photo["has_medium"]) {
		$width = 640;
		$height = 853;
	}
	if ($photo["has_large"]) {
		$width = 1080;
		$height = 1440;
	}
} else if ($photo["aspect_width"] == 1 && $photo["aspect_height"] == 1) {
	$width = 320;
	$height = 320;
	if ($photo["has_medium"]) {
		$width = 640;
		$height = 640;
	}
	if ($photo["has_large"]) {
		$width = 1080;
		$height = 1080;
	}
} else if ($photo["aspect_width"] == 4 && $photo["aspect_height"] == 3) {
	$width = 320;
	$height = 240;
	if ($photo["has_medium"]) {
		$width = 640;
		$height = 480;
	}
	if ($photo["has_large"]) {
		$width = 1440;
		$height = 1080;
	}
} else if ($photo["aspect_width"] == 16 && $photo["aspect_height"] == 9) {
	$width = 320;
	$height = 180;
	if ($photo["has_medium"]) {
		$width = 640;
		$height = 360;
	}
	if ($photo["has_large"]) {
		$width = 1920;
		$height = 1080;
	}
}
$path = public_path($photo["time"]) . "/p$photo_id.{$width}x{$height}.jpg";
$photo_url = "$protocol://$server_name$path?" . fs_time("$doc_root/www$path");

writeln('<table style="border: 1px #d3d3d3 solid; margin-bottom: 8px;">');
writeln('	<tr>');
writeln('		<td style="background-color: #eeeeee; padding: 8px;"><img alt="photo" src="' . $photo_url . '"/></td>');
writeln('	</tr>');
writeln('</table>');

end_main();
print_footer();
