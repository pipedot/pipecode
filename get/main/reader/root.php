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

if (!string_uses($s2, "[a-z]")) {
	die("invalid topic");
}
$slug = $s2;
$topic = db_get_rec("reader_topic", ["slug" => $slug]);

print_header($topic["name"]);
beg_main();

$items_per_page = 50;
list($item_start, $page_footer) = page_footer("feed", $items_per_page);

$row = sql("select * from feed order by title limit $item_start, $items_per_page");
beg_tab();
writeln('	<tr>');
writeln('		<th>Title</th>');
writeln('		<th class="right">Updated</th>');
writeln('	</tr>');
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

	writeln('	<tr>');
	writeln('		<td><a class="favicon-16"' . $icon . ' href="/feed/' . $row[$i]["slug"] . '">' . $title . '</a></td>');
	writeln('		<td class="right">' . date("Y-m-d H:i", $row[$i]["time"]) . '</td>');
	writeln('	</tr>');
}
end_tab();

writeln($page_footer);

end_main();
print_footer();
