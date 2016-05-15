<?
//
// Pipecode - distributed social network
// Copyright (C) 2014-2016 Bryan Beicker <bryan@pipedot.org>
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

set_time_limit(15 * MINUTES);

include("image.php");

require_editor();

clean_tmp_images();
$story = item_request(TYPE_STORY);
$story_code = $story["short_code"];
$images = build_preview_images($story["body"]);

$spinner[] = ["name" => "Story", "link" => "/story/"];
$spinner[] = ["name" => $story["title"], "short" => $story_code, "link" => "/story/$story_code"];
$spinner[] = ["name" => "Image", "link" => "/story/$story_code/image"];

print_header(["form" => true]);

writeln('<label style="border: 1px solid #888888; border-radius: 4px; float: left; padding: 8px; margin-right: 8px; margin-bottom: 8px;">');
writeln('	<table>');
writeln('		<tr>');
writeln('			<td style="vertical-align: middle;"><input name="tmp_image_id" value="0" type="radio" checked></td>');
writeln('			<td><img alt="thumbnail" src="/images/missing-128.png"></td>');
writeln('		</tr>');
writeln('		<tr>');
writeln('			<td colspan="2" style="padding-top: 4px; text-align: center">' . get_text('No Image') . '</td>');
writeln('		</tr>');
writeln('	</table>');
writeln('</label>');

for ($i = 0; $i < count($images); $i++) {
	$tmp_image = db_get_rec("tmp_image", $images[$i]);
	$path = public_path($tmp_image["time"]);

	writeln('<label style="border: 1px solid #888888; border-radius: 4px; float: left; padding: 8px; margin-right: 8px; margin-bottom: 8px;">');
	writeln('	<table>');
	writeln('		<tr>');
	writeln('			<td style="vertical-align: middle;"><input name="tmp_image_id" value="' . $images[$i] . '" type="radio"></td>');
	writeln('			<td><img alt="thumbnail" src="' . $path . '/t' . $images[$i] . '.128x128.jpg"></td>');
	writeln('		</tr>');
	writeln('		<tr>');
	writeln('			<td colspan="2" style="padding-top: 4px; text-align: center">' . $tmp_image["original_width"] . ' x ' . $tmp_image["original_height"] . '</td>');
	writeln('		</tr>');
	writeln('	</table>');
	writeln('</label>');
}

box_right("Continue");

print_footer(["form" => true]);
