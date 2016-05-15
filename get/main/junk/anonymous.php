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

require_editor();

$junk = true;

$spinner[] = ["name" => "Junk", "link" => "/junk/"];
$spinner[] = ["name" => "Anonymous", "link" => "/anonymous"];

print_header(["title" => "Anonymous Comments", "form" => true]);

writeln('<h1>' . get_text('Anonymous Comments') . '</h1>');

$row = sql("select comment_id, article_id, body, edit_time, junk_status, subject, zid from comment where zid = '' and junk_status = 0 order by publish_time desc limit 0, 100");
if (count($row) == 0) {
	writeln('<p>' . get_text('No unmarked anonymous comments') . '</p>');
} else {
	for ($i = 0; $i < count($row); $i++) {
		print_comment($row[$i], true);
	}

	box_two('<a href="?default=spam">' . get_text('Default to Spam') . '</a>', "Save");
}

//<div id="select_all">Select All</div>
//<script>
//$('#select_all').click(function () {
//	alert("hello");
//});
//</script>

print_footer(["form" => true]);
