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

if (!$auth_user["admin"] && !$auth_user["editor"]) {
	die("not an editor or admin");
}

$junk = true;
//$junk_default = true;

print_header("Junk");
beg_main();

writeln('<h1>Junk</h1>');

$row = sql("select comment_vote.comment_id, count(*) as votes, root_id, short_id, subject, type, edit_time, body, comment.zid from comment_vote inner join comment on comment_vote.comment_id = comment.comment_id where reason = 'Spam' and junk_status = 0 group by comment_id");
if (count($row) == 0) {
	writeln('<p>No unmarked junk comments</p>');
} else {
	beg_form();
	for ($i = 0; $i < count($row); $i++) {
		$a = article_info($row[$i], false);
		print render_comment($row[$i]["subject"], $row[$i]["zid"], $row[$i]["edit_time"], $row[$i]["comment_id"], $row[$i]["body"], 0, $row[$i]["short_id"], $a["link"], $a["title"]);
		writeln('</div>');
		writeln('</article>');
		writeln();
	}

	left_right_box("<a href=\"?default=spam\">Default to Spam</a>", "Save");
	end_form();
}

writeln('<div style="margin-top: 8px; margin-bottom: 8px; text-align: center"><a href="anonymous">Show All Anonymous</a></div>');

end_main();
print_footer();
