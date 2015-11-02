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

include("story.php");

print_header("Submissions", [], [], [], ["Submissions"], ["/submissions"]);
beg_main();

$items_per_page = 10;
list($item_start, $page_footer) = page_footer("story", $items_per_page, ["author_zid" => $zid]);

$row = sql("select story_id from story where author_zid = ? order by publish_time desc limit $item_start, $items_per_page", $zid);
if (count($row) == 0) {
	if ($auth_zid === $zid) {
		writeln('<p>' . get_text('You have no accepted story submissions yet. <a href="$1">Submit</a> one now!', "$protocol://$server_name/submit") . '</p>');
	} else {
		writeln('<p>' . get_text('This user has no accepted submissions yet.') . '</p>');
	}
}
for ($i = 0; $i < count($row); $i++) {
	print_story($row[$i]["story_id"]);
}

writeln($page_footer);

end_main();
print_footer();

