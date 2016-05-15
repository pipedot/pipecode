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

include("clean.php");

$card = item_request(TYPE_CARD);
$tags = clean_tags();
list($clean_body, $dirty_body) = clean_body();

require_mine($card["zid"]);

sql("delete from card_tags where card_id = ?", $card["card_id"]);
for ($i = 0; $i < count($tags); $i++) {
	$card_tags = db_new_rec("card_tags");
	$card_tags["card_id"] = $card["card_id"];
	$card_tags["tag"] = $tags[$i];
	db_set_rec("card_tags", $card_tags);
}

if ($card["body"] !== $clean_body) {
	$card_edit = db_new_rec("card_edit");
	$card_edit["card_id"] = $card["card_id"];
	$card_edit["body"] = $card["body"];
	$card_edit["edit_time"] = $card["edit_time"];
	db_set_rec("card_edit", $card_edit);

	$card["body"] = $clean_body;
	$card["edit_time"] = time();
	db_set_rec("card", $card);

	sql("delete from stream_vote where article_id = ? and value > 0", $card["card_id"]);
}

header("Location: /card/{$card["short_code"]}");

