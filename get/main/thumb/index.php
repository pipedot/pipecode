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

include("drive.php");

$spinner[] = ["name" => "Thumbnail", "link" => "/thumb/"];

print_header(["title" => "Thumbnails"]);

$items_per_page = 100;
list($item_start, $page_footer) = page_footer("thumb", $items_per_page);

$row = sql("select thumb_id from thumb order by time desc limit $item_start, $items_per_page");
writeln('<div class="gallery">');
foreach ($row as $thumb) {
	$thumb_code = crypt_crockford_encode($thumb["thumb_id"]);
	writeln('	<a href="' . $thumb_code . '"><img alt="thumbnail" class="thumb" src="' . $thumb_code . '.jpg"></a>');
}
writeln('</div>');

writeln($page_footer);

print_footer();
