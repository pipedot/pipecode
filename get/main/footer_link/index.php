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

require_admin();

$spinner[] = ["name" => "Footer Link", "link" => "/footer_link/"];

print_header(["title" => "Footer Links"]);

//writeln('<h1>' . get_text('Footer Links') . '</h1>');

dict_beg();
$list = db_get_list("footer_link", "name");
foreach ($list as $item) {
	if (!$item["icon"]) {
		$item["icon"] = "globe";
	}
	dict_row('<a class="icon-16 ' . $item["icon"] . '-16" href="' . $item["slug"] . '">' . $item["name"] . '</a>', '<a class="icon-16 minus-16" href="' . $item["slug"] . '/remove">Remove</a>');
}
dict_end();
box_right('<a class="icon-16 plus-16" href="add">Add</a>');

print_footer();
