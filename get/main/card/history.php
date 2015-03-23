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

include("stream.php");
include("diff.php");

$card = item_request("card");

print_header("Card History");
beg_main();
beg_form();

writeln('<h1>Card</h1>');

print_card($card["card_id"], "large");

$row = sql("select * from card_edit where card_id = ? order by edit_time", $card["card_id"]);
if (count($row) > 0) {
	writeln('<h2>History</h2>');
	for ($i = 0; $i < count($row); $i++) {
		$old_body = $row[$i]["body"];
		if ($i == count($row) - 1) {
			$new_body = $card["body"];
		} else {
			$new_body = $row[$i + 1]["body"];
		}
		$diff = diff($old_body, $new_body);

		writeln('<div class="edit-title">' . date("Y-m-d H:i", $row[$i]["edit_time"]) . '</div>');
		writeln('<div class="edit-body">' . $diff . '</div>');
	}
}

end_main();
print_footer();

