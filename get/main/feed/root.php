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

$feed = item_request(TYPE_FEED);
$feed_code = crypt_crockford_encode($feed["feed_id"]);

print_header($feed["title"]);
beg_main();

if (fs_is_file("$doc_root/www/pub/favicon/$feed_code.png")) {
	$icon = ' style="background-image: url(/pub/favicon/' . $feed_code . '.png)"';
} else {
	$icon = "";
}

writeln('<h1 class="favicon-16"' . $icon . '>' . $feed["title"] . '</h1>');

dict_beg();
dict_row("Link", '<a href="' . $feed["link"] . '">' . $feed["link"] . '</a>');
dict_row("Feed", '<a href="' . $feed["uri"] . '">' . $feed["uri"] . '</a>');
if ($feed["copyright"] != "") {
	dict_row("Copyright", $feed["copyright"]);
}
dict_row("Updated", date("Y-m-d H:i", $feed["time"]));
dict_end();

if ($auth_user["admin"]) {
	box_right('<a class="icon-16 notepad-16" href="/feed/' . $feed_code . '/edit">' . get_text('Edit') . '</a>');
}

$items_per_page = 50;
list($item_start, $page_footer) = page_footer("select count(*) as item_count from article where feed_id = ?", $items_per_page, array($feed["feed_id"]));

$row = sql("select * from article where feed_id = ? order by publish_time desc limit $item_start, $items_per_page", $feed["feed_id"]);

for ($i = 0; $i < count($row); $i++) {
	print_news($row[$i]);
}

writeln($page_footer);

end_main();
print_footer();
