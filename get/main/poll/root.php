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

include("render.php");
include("poll.php");

$poll = item_request("poll");
/*$date = $s2;
$slug = $s3;
$time_beg = strtotime("$date GMT");
if ($time_beg === false) {
	die("invalid date [$date]");
}
$time_end = $time_beg + 86400;
$row = sql("select poll_id from poll where time > ? and time < ? and slug = ? order by time", $time_beg, $time_end, $slug);
if (count($row) == 0) {
	die("poll not found - date [$date] title [$slug]");
}
$poll_id = $row[0]["poll_id"];

$poll = db_get_rec("poll", $poll_id);*/
$clean = clean_url($poll["question"]);
$type_id = $poll["type_id"];

print_header("Poll");
print_main_nav("poll");
beg_main("cell");

vote_box($poll["poll_id"], false);
print_comments("poll", $poll);

end_main();
print_footer();
