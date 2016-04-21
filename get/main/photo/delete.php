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

$photo = item_request(TYPE_PHOTO);
$photo_code = $photo["short_code"];

if ($photo["zid"] !== $auth_zid) {
	fatal("Not your photo");
}

$path = public_path($photo["time"]) . "/photo_{$photo["short_code"]}_256x256.jpg";
$photo_url = "$protocol://$server_name$path?" . fs_time("$doc_root/www$path");

$spinner[] = ["name" => "Photo", "link" => "/photo/"];
$spinner[] = ["name" => $photo_code, "link" => "/photo/$photo_code"];
$spinner[] = ["name" => "Remove", "link" => "/photo/$photo_code/remove"];

print_header(["form" => true]);

writeln('<h1>' . get_text('Delete Photo') . '</h1>');
writeln('<p>' . get_text('Are you sure you want to delete this photo?') . '</p>');

writeln('<table style="border: 1px #d3d3d3 solid; margin-bottom: 8px;">');
writeln('	<tr>');
writeln('		<td style="background-color: #eeeeee; padding: 8px;"><img style="width: 128px" src="' . $photo_url . '"></td>');
writeln('	</tr>');
writeln('</table>');

box_left("Remove");

print_footer(["form" => true]);
