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

$spinner[] = ["name" => "Feed", "link" => "/feed/"];

print_header();

$items_per_page = 50;
list($item_start, $page_footer) = page_footer("feed", $items_per_page);

// TODO: delete soon
writeln('<div class="balloon">');
writeln('	<dl class="dl-32 bulb-32">');
writeln('		<dt>Link Changed</dt>');
writeln('		<dd>This page used to be a <a href="' . $protocol . '://bryan.' . $server_name . '/feed/">hard link to a sample user\'s feed page</a>. Please update your links.</dd>');
writeln('	</dl>');
writeln('</div>');

dict_beg();
$row = sql("select * from feed order by title limit $item_start, $items_per_page");
for ($i = 0; $i < count($row); $i++) {
	$short_code = crypt_crockford_encode($row[$i]["feed_id"]);
	if ($row[$i]["title"] == "") {
		$title = "(none)";
	} else {
		$title = $row[$i]["title"];
	}
	if (fs_is_file("$doc_root/www/pub/favicon/$short_code.png")) {
		$icon = ' style="background-image: url(/pub/favicon/' . $short_code . '.png)"';
	} else {
		$icon = "";
	}

	//dict_row('<a class="favicon-16"' . $icon . ' href="/feed/' . $row[$i]["slug"] . '">' . $title . '</a>', date("Y-m-d H:i", $row[$i]["time"]));
	dict_row('<a class="favicon-16"' . $icon . ' href="/feed/' . $row[$i]["slug"] . '">' . $title . '</a>', '<a class="icon-16 plus-16" href="/feed/' . $row[$i]["slug"] . '/add">' . get_text('Add') . '</a>');
}
dict_end();

writeln($page_footer);

print_footer();
