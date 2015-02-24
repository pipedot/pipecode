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

$feed = find_rec("feed");
$short_code = crypt_crockford_encode($feed["feed_id"]);

print_header($feed["title"]);
beg_main();

if (fs_is_file("$doc_root/www/pub/favicon/$short_code.png")) {
	$icon = ' style="background-image: url(/pub/favicon/' . $short_code . '.png)"';
} else {
	$icon = "";
}
//writeln('<table class="zebra">');
//writeln('	<tr>');
//writeln('		<td><div class="favicon_16"' . $icon . '>' . $feed["title"] . '</div></td>');
//writeln('	</tr>');
//writeln('</table>');

writeln('<h1 class="favicon_16"' . $icon . '>' . $feed["title"] . '</h1>');

dict_beg();
dict_row("Link", '<a href="' . $feed["link"] . '">' . $feed["link"] . '</a>');
dict_row("Feed", '<a href="' . $feed["uri"] . '">' . $feed["uri"] . '</a>');
if ($feed["copyright"] != "") {
	dict_row("Copyright", $feed["copyright"]);
}
dict_row("Updated", date("Y-m-d H:i", $feed["time"]));
dict_end();

if ($auth_user["admin"]) {
	right_box("<a class=\"icon_16 notepad_16\" href=\"/feed/$short_code/edit\">Edit</a>");
}

$items_per_page = 50;
list($item_start, $page_footer) = page_footer("select count(*) as item_count from article where feed_id = ?", $items_per_page, array($feed["feed_id"]));

$row = sql("select * from article where feed_id = ? order by publish_time desc limit $item_start, $items_per_page", $feed["feed_id"]);
/*
beg_tab();
writeln('	<tr>');
writeln('		<th>Title</th>');
writeln('		<th class="right">Time</th>');
writeln('	</tr>');
for ($i = 0; $i < count($row); $i++) {
	$short_code = crypt_crockford_encode($row[$i]["article_id"]);

	writeln('	<tr>');
	writeln('		<td><a href="/article/' . $short_code . '">' . $row[$i]["title"] . '</a></td>');
	writeln('		<td class="right">' . date("Y-m-d H:i", $row[$i]["publish_time"]) . '</td>');
	writeln('	</tr>');
}
end_tab();
*/

for ($i = 0; $i < count($row); $i++) {
	$short_code = crypt_crockford_encode($row[$i]["article_id"]);
	//if ($row[$i]["image_id"] > 0) {
	//	$image_id = $row[$i]["image_id"];
	//	$image = db_get_rec("image", $image_id);
	//	$image_path = public_path($image["time"]) . "/i$image_id.256x256.jpg";
	//} else {
	//	$image_path = "";
	//}
	if ($row[$i]["thumb_id"] > 0) {
		$thumb_code = crypt_crockford_encode($row[$i]["thumb_id"]);
	} else {
		$thumb_code = "";
	}

	$info = "on " . date("Y-m-d H:i", $row[$i]["publish_time"]);
	if ($row[$i]["author_name"] != "") {
		$by = $row[$i]["author_name"];
		if ($row[$i]["author_link"] != "") {
			//$by = '<a href="' . $row[$i]["author_link"] . '" rel="nofollow">' . $by . '</a>';
			$by = '<a href="' . $row[$i]["author_link"] . '" rel="author">' . $by . '</a>';
		}
		$info = "<address>$by</address> $info";
	}

	if ($thumb_code == "") {
		writeln('<article class="article_text_row">');
		writeln('<table>');
		writeln('	<tr>');
		writeln('		<td>');
		writeln('			<div class="article_preview">');
		writeln('				<div class="article_link"><a href="/article/' . $short_code . '">' . $row[$i]["title"] . '</a></div>');
		writeln('				<div class="article_info">' . $info . '</div>');
		writeln('				<div class="article_description">' . $row[$i]["description"] . '</div>');
		writeln('			</div>');
		writeln('		</td>');
		writeln('	</tr>');
		writeln('</table>');
		writeln('</article>');
	} else {
		writeln('<article class="article_image_row">');
		writeln('<table>');
		writeln('	<tr>');
		writeln('		<td><a href="/article/' . $short_code . '"><img class="thumb" src="/thumb/' . $thumb_code . '.jpg"/></a><td>');
		writeln('		<td>');
		//writeln('			<a class="article_image" href="/article/' . $short_code . '" style="background-image: url(' . $image_path . ')"></a>');
		writeln('			<div class="article_preview">');
		writeln('				<div class="article_link"><a href="/article/' . $short_code . '">' . $row[$i]["title"] . '</a></div>');
		writeln('				<div class="article_info">' . $info . '</div>');
		writeln('				<div class="article_description">' . $row[$i]["description"] . '</div>');
		writeln('			</div>');
		writeln('		</td>');
		writeln('	</tr>');
		writeln('</table>');
		writeln('</article>');
	}
}

writeln($page_footer);

end_main();
print_footer();
