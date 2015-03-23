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

if ($auth_zid === "") {
	die("sign in to donate");
}

$story = item_request("story");
$link = item_link("story", $story_id);

print_header("Donate - " . $story["title"]);
print_left_bar("main", "stories");
beg_main("cell");
beg_form();

writeln('<h1>Story</h1>');
box_left('<a class="icon-16 news-16" href="' . $link . '">' . $story["title"] . '</a>');

writeln('<h2>Your Balance</h2>');
$row = sql("select sum(amount) as balance from accounting.posting where zid = ?", $auth_zid);
$balance = (int) $row[0]["balance"];
writeln('<div class="icon_32 coins_32">$' . format_money($balance) . '</div>');

writeln('<h2>Donate</h2>');
beg_tab();
print_row(array("caption" => "Amount", "text_key" => "amount", "text_value" => ($balance < 100 ? format_money($balance) : "1.00")));
end_tab();

box_right("Continue");

end_form();
end_main();
print_footer();
