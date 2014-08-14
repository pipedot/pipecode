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

include("stream.php");

$card_id = (int) $s2;
$card = db_get_rec("card", $card_id);

if ($auth_zid == "") {
	print_header("Card");
} else {
	print_header("Card", array("Share"), array("notepad"), array(user_page_link($auth_zid) . "stream/share"));
}
beg_main();

writeln('<h1>Card</h1>');

print_card($card_id);

end_main();
print_footer();


