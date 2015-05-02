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

include("image.php");

$card = item_request(TYPE_CARD);

$tmp_image_id = http_post_int("tmp_image_id");

if ($tmp_image_id > 0) {
	$image_id = promote_image($tmp_image_id);
} else {
	$image_id = 0;
}
$card["image_id"] = $image_id;
db_set_rec("card", $card);

header("Location: $protocol://$server_name/card/{$card["short_code"]}");
