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

include("story.php");
include("drive.php");

$ext = fs_ext($s2);
if ($ext == "jpg") {
	$short_code = substr($s2, 0, -4);
	if (!string_uses($short_code, "[A-Z][0-9]")) {
		fatal("Invalid short code");
	}
	$thumb_id = crypt_crockford_decode($short_code);

	$thumb = db_get_rec("thumb", $thumb_id);
	$data = drive_get($thumb["hash"]);

	if (!http_modified($thumb["time"], $thumb["hash"])) {
		http_response_code(304);
	} else {
		header("Content-type: image/jpeg");
		header("Content-length: " . strlen($data));
		header_expires(90 * DAYS);
		header_last_modified($thumb["time"]);
		header("ETag: \"" . $thumb["hash"] . "\"");

		print $data;
	}
	die();
}

print_header("Thumbnail");
beg_main();

$thumb = item_request(TYPE_THUMB);
$short_code = crypt_crockford_encode($thumb["thumb_id"]);

if ($thumb["low_res"]) {
	$size = get_text('Small') . ' (128x128)';
} else {
	$size = get_text('Large') . ' (256x256)';
}

writeln('<h1>' . get_text('Thumbnail') . '</h1>');
writeln('<div class="photo-frame">');
writeln('	<img alt="thumbnail" class="thumb" src="' . $short_code . '.jpg">');
writeln('	<div><a href="' . $short_code . '.jpg">' . $size . '</a></div>');
writeln('</div>');

$items_per_page = 50;
list($item_start, $page_footer) = page_footer("select count(*) as item_count from article where thumb_id = ?", $items_per_page, array($thumb["thumb_id"]));

$row = sql("select article_id, author_name, author_link, article.description, publish_time, article.title, feed.slug as feed_slug, feed.title as feed_title from article inner join feed on article.feed_id = feed.feed_id where thumb_id = ? limit $item_start, $items_per_page", $thumb["thumb_id"]);
if (count($row) > 0) {
	writeln('<h2>Articles</h2>');

	for ($i = 0; $i < count($row); $i++) {
		print_news($row[$i]);
	}

	writeln($page_footer);
}

end_main();
print_footer();

