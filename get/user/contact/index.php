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

require_mine();

$spinner[] = ["name" => "Contact", "link" => "/contact/"];
$actions[] = ["name" => "Add", "icon" => "plus", "link" => "/contact/add"];

print_header(["title" => "Contacts"]);

$items_per_page = 50;
list($item_start, $page_footer) = page_footer("contact", $items_per_page, ["zid" => $zid]);
$row = sql("select contact_id, name, email from contact where zid = ? order by name limit $item_start, $items_per_page", $zid);

dict_beg();

if (count($row) == 0) {
	dict_none();
}
for ($i = 0; $i < count($row); $i++) {
	$name = $row[$i]["name"];
	if ($name == "") {
		$name = "(blank)";
	}
	$email = $row[$i]["email"];
	if ($email == "") {
		$name = "(blank)";
	}

	dict_row('<a href="' . $row[$i]["contact_id"] . '">' . $name . '</a>', '<a href="/mail/compose?cid=' . $row[$i]["contact_id"] . '">' . $email . '</a>');
}
dict_end();

writeln($page_footer);

print_footer();
