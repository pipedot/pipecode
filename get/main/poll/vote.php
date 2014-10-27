<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
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

include("poll.php");

$poll_id = $s2;
if (!string_uses($poll_id, "[a-z][0-9]_")) {
	die("invalid poll_id [$poll_id]");
}

$poll = db_get_rec("poll", $poll_id);
$type_id = $poll["type_id"];

print_header("Poll");
print_left_bar("main", "poll");
beg_main("cell");

vote_box($poll_id, true);

end_main();
print_footer();
