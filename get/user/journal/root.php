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

include("render.php");
include("story.php");
include("image.php");

$journal = find_rec("journal");

if (!$journal["published"] && $zid != $auth_zid) {
	die("not published");
}

if ($auth_zid === $zid) {
	print_header("Journal", array("Write"), array("notepad"), array("/journal/write"));
} else {
	print_header("Journal");
}
print_left_bar("user", "journal");
beg_main("cell");

print_journal($journal["journal_id"]);
print_comments("journal", $journal);

end_main();
print_footer();
