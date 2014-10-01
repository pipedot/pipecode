<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
//
// This file is part of Pipecode.
//
// Pipecode is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Pipecode is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Pipecode.  If not, see <http://www.gnu.org/licenses/>.
//

if ($auth_zid === "") {
	die("sign in to donate");
}
if (string_uses($s2, "[A-Z][a-z][0-9]")) {
	$short_id = crypt_crockford_decode($s2);
	$short = db_get_rec("short", $short_id);
	if ($short["type"] != "story") {
		die("invalid short code [$s2]");
	}
	$story_id = $short["item_id"];
} else if (string_uses($s2, "[a-z][0-9]_")) {
	$story_id = $s2;
} else {
	die("unknown story [$s2]");
}

$story = db_get_rec("story", $story_id);
$link = item_link("story", $story_id);

print_header("Donate - " . $story["title"]);
print_left_bar("main", "stories");
beg_main("cell");
beg_form();

writeln('<h1>Story</h1>');
writeln('<a class="icon_16 news_16" href="' . $link . '" style="margin-bottom: 8px;">' . $story["title"] . '</a>');

writeln('<h2>Your Balance</h2>');
$row = sql("select sum(amount) as balance from accounting.posting where zid = ?", $auth_zid);
$balance = (int) $row[0]["balance"];
writeln('<div class="icon_32 coins_32">$' . format_money($balance) . '</div>');

writeln('<h2>Donate</h2>');
beg_tab();
print_row(array("caption" => "Amount", "text_key" => "amount", "text_value" => ($balance < 100 ? format_money($balance) : "1.00")));
end_tab();

right_box("Continue");

end_form();
end_main();
print_footer();
