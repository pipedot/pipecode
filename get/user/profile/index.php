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

print_header("Profile", [], [], [], ["Profile"], ["/profile/"]);
beg_main("dual-table");
writeln('<div class="dual-left">');

dict_beg("Information");
if ($user_conf["show_name_enabled"] && $user_conf["display_name"] != "") {
	dict_row('<span class="icon-16 user-16">Name</span>', $user_conf["display_name"]);
}
if ($user_conf["show_birthday_enabled"] && $user_conf["birthday"] != 0) {
	dict_row('<span class="icon-16 cake-16">Birthday</span>', gmdate("F j", $user_conf["birthday"]));
}
if ($user_conf["show_email_enabled"] && $user_conf["email"] != "") {
	dict_row('<span class="icon-16 mail-16">Email</span>', '<a href="mailto:' . $user_conf["email"] . '">' . $user_conf["email"] . '</a>');
}
if ($user_conf["joined"] != 0) {
	dict_row('<span class="icon-16 calendar-16">Joined</span>', date("Y-m-d", $user_conf["joined"]));
}
dict_end();

if ($zid === $auth_zid) {
	box_right('<a class="icon-16 tools-16" href="settings">Settings</a>');
}

$row = sql("select story_id, publish_time, slug, title from story where author_zid = ? order by publish_time desc limit 10", $zid);
if (count($row) > 0) {
	beg_tab();
	writeln('	<tr>');
	writeln('		<th>Recent Submissions</th>');
	writeln('		<th class="center">Comments</th>');
	writeln('		<th class="right">Date</th>');
	writeln('	</tr>');
	for ($i = 0; $i < count($row); $i++) {
		$comments = count_comments(TYPE_STORY, $row[$i]["story_id"]);
		writeln('	<tr>');
		writeln('		<td><a href="' . item_link(TYPE_STORY, $row[$i]["story_id"], $row[$i]) . '">' . $row[$i]["title"] . '</a></td>');
		writeln('		<td class="center">' . $comments["count"] . '</td>');
		writeln('		<td class="right nowrap">' . gmdate("Y-m-d", $row[$i]["publish_time"]) . '</td>');
		writeln('	</tr>');
	}
	end_tab();
	box_right('<a class="icon-16 news-16" href="/submissions">Submissions</a>');
}

$row = sql("select journal_id, published, publish_time, slug, title, zid from journal where zid = ? and published = 1 order by publish_time desc limit 10", $zid);
if (count($row) > 0) {
	beg_tab();
	writeln('	<tr>');
	writeln('		<th>Recent Journals</th>');
	writeln('		<th class="center">Comments</th>');
	writeln('		<th class="right">Date</th>');
	writeln('	</tr>');
	for ($i = 0; $i < count($row); $i++) {
		$comments = count_comments(TYPE_JOURNAL, $row[$i]["journal_id"]);
		writeln('	<tr>');
		writeln('		<td><a href="' . item_link(TYPE_JOURNAL, $row[$i]["journal_id"], $row[$i]) . '">' . $row[$i]["title"] . '</a></td>');
		writeln('		<td class="center">' . $comments["count"] . '</td>');
		writeln('		<td class="right nowrap">' . gmdate("Y-m-d", $row[$i]["publish_time"]) . '</td>');
		writeln('	</tr>');
	}
	end_tab();
	box_right('<a class="icon-16 notepad-16" href="/journal/">Journals</a>');
}

writeln('</div>');
writeln('<div class="dual-right">');

beg_tab("Avatar");
writeln('	<tr>');
writeln('		<td class="center"><a href="/avatar/"><img alt="Avatar" class="thumb" src="' . avatar_picture($zid, 256) . '"></a></td>');
writeln('	</tr>');
writeln('</table>');
box_right('<a class="icon-16 picture-16" href="/avatar/">Avatars</a>');

if ($auth_user["show_junk_enabled"]) {
	$row = sql("select comment_id, root_id, junk_status, subject, edit_time, body from comment where zid = ? order by edit_time desc limit 10", $zid);
} else {
	$row = sql("select comment_id, root_id, junk_status, subject, edit_time, body from comment where junk_status <= 0 and zid = ? order by edit_time desc limit 10", $zid);
}
if (count($row) > 0) {
	beg_tab();
	writeln('	<tr>');
	writeln('		<th>Recent Comments</th>');
	writeln('		<th class="center">Score</th>');
	//writeln('		<th class="center">Replies</th>');
	writeln('		<th class="right">Date</th>');
	writeln('	</tr>');
	for ($i = 0; $i < count($row); $i++) {
		list($score, $reason) = get_comment_score($row[$i]["comment_id"]);
		$score_reason = $score;
		if ($reason != "") {
			$score_reason .= ", $reason";
		}

		writeln('	<tr>');
		writeln('		<td><a href="' . item_link(TYPE_COMMENT, $row[$i]["comment_id"], $row[$i]) . '">' . $row[$i]["subject"] . '</a></td>');
		writeln('		<td class="center">' . $score_reason . '</td>');
		//writeln('		<td class="center">' . $score_reason . '</td>');
		writeln('		<td class="right nowrap">' . gmdate("Y-m-d", $row[$i]["edit_time"]) . '</td>');
		writeln('	</tr>');
	}
	end_tab();
	box_right('<a class="icon-16 chat-16" href="/comments">Comments</a>');
}

writeln('</div>');
end_main();
print_footer();
