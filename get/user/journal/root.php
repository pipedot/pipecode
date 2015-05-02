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
include("story.php");
include("image.php");

$journal = item_request(TYPE_JOURNAL);
$journal_link = item_link(TYPE_JOURNAL, $journal["journal_id"], $journal);

if (!$journal["published"] && $zid != $auth_zid) {
	die("not published");
}

if ($auth_zid === $zid) {
	print_header("Journal", ["Write"], ["notepad"], ["/journal/write"], ["Journal", $journal["title"]], ["/journal/", $journal_link]);
} else {
	print_header("Journal");
}
print_user_nav("journal");
beg_main("cell");

print_journal($journal["journal_id"]);
print_comments(TYPE_JOURNAL, $journal);

end_main();
print_footer();
