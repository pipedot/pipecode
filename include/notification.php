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

function print_notification_box()
{
	global $auth_zid;
	global $notification_count;

	if ($notification_count == 0) {
		return;
	}

	writeln('<div class="dialog-title">Notifications</div>');
	writeln('<div class="dialog-middle side-link-one">');
	//writeln('<div class="notification-box">');

	$row = sql("select notification_id, item_id, parent_id, type_id from notification where zid = ? order by time desc limit 5", $auth_zid);
	for ($i = 0; $i < count($row); $i++) {
		notification_small($row[$i]["notification_id"], $row[$i]["item_id"], $row[$i]["parent_id"], $row[$i]["type_id"]);
	}
/*

	writeln('<a href="/comment/1">');
	writeln('	<dl class="dl-32 notepad-32">');
	writeln('		<dt>Journal "Solar Panels Part 3"</dt>');
	writeln('		<dd>bryan@pipedot.net</dd>');
	writeln('	</dl>');
	writeln('</a>');

	writeln('<a href="/comment/1">');
	writeln('	<dl class="dl-32 chat-32">');
	writeln('		<dt>Reply to "second level"</dt>');
	writeln('		<dd>zenbi@pipedot.net</dd>');
	writeln('	</dl>');
	writeln('</a>');

	writeln('<a href="/comment/1">');
	writeln('	<dl class="dl-32 chat-32">');
	writeln('		<dt>Reply to "This is a really long subject line that will probably need to be wrapped"</dt>');
	writeln('		<dd>zenbi@pipedot.net</dd>');
	writeln('	</dl>');
	writeln('</a>');

	writeln('<a href="/comment/1">');
	writeln('	<dl class="dl-32 mail-32">');
	writeln('		<dt>Message "Hello World"</dt>');
	writeln('		<dd>zenbi@pipedot.net</dd>');
	writeln('	</dl>');
	writeln('</a>');

	$score = "0";
	writeln('<a href="/comment/1">');
	writeln('	<dl class="dl-32 ' . score_icon($score) . '-32">');
	writeln('		<dt>Moderation to "This is a really long subject line that will probably need to be wrapped"</dt>');
	writeln('		<dd>Score: ' . $score . '</dd>');
	writeln('	</dl>');
	writeln('</a>');

	$score = "1";
	writeln('<a href="/comment/1">');
	writeln('	<dl class="dl-32 ' . score_icon($score) . '-32">');
	writeln('		<dt>Moderation to "This is a really long subject line that will probably need to be wrapped"</dt>');
	writeln('		<dd>Score: ' . $score . '</dd>');
	writeln('	</dl>');
	writeln('</a>');

	$score = "2";
	writeln('<a href="/comment/1">');
	writeln('	<dl class="dl-32 ' . score_icon($score) . '-32">');
	writeln('		<dt>Moderation to "This is a really long subject line that will probably need to be wrapped"</dt>');
	writeln('		<dd>Score: ' . $score . '</dd>');
	writeln('	</dl>');
	writeln('</a>');

	$score = "3";
	writeln('<a href="/comment/1">');
	writeln('	<dl class="dl-32 ' . score_icon($score) . '-32">');
	writeln('		<dt>Moderation to "This is a really long subject line that will probably need to be wrapped"</dt>');
	writeln('		<dd>Score: ' . $score . '</dd>');
	writeln('	</dl>');
	writeln('</a>');

	$score = "4";
	writeln('<a href="/comment/1">');
	writeln('	<dl class="dl-32 ' . score_icon($score) . '-32">');
	writeln('		<dt>Moderation to "This is a really long subject line that will probably need to be wrapped"</dt>');
	writeln('		<dd>Score: ' . $score . '</dd>');
	writeln('	</dl>');
	writeln('</a>');

	$score = "5";
	writeln('<a href="/comment/1">');
	writeln('	<dl class="dl-32 ' . score_icon($score) . '-32">');
	writeln('		<dt>Moderation to "This is a really long subject line that will probably need to be wrapped"</dt>');
	writeln('		<dd>Score: ' . $score . '</dd>');
	writeln('	</dl>');
	writeln('</a>');

	$score = "2, Offtopic";
	writeln('<a href="/comment/1">');
	writeln('	<dl class="dl-32 ' . score_icon($score) . '-32">');
	writeln('		<dt>Moderation to "This is a really long subject line that will probably need to be wrapped"</dt>');
	writeln('		<dd>Score: ' . $score . '</dd>');
	writeln('	</dl>');
	writeln('</a>');

	$score = "3, Funny";
	writeln('<a href="/comment/1">');
	writeln('	<dl class="dl-32 ' . score_icon($score) . '-32">');
	writeln('		<dt>Moderation to "This is a really long subject line that will probably need to be wrapped"</dt>');
	writeln('		<dd>Score: ' . $score . '</dd>');
	writeln('	</dl>');
	writeln('</a>');

	$score = "4, Interesting";
	writeln('<a href="/comment/1">');
	writeln('	<dl class="dl-32 ' . score_icon($score) . '-32">');
	writeln('		<dt>Moderation to "This is a really long subject line that will probably need to be wrapped"</dt>');
	writeln('		<dd>Score: ' . $score . '</dd>');
	writeln('	</dl>');
	writeln('</a>');

	$zid = "zenbi@pipedot.net";
	writeln('<a href="' . user_link($zid) . 'profile/">');
	writeln('	<dl class="dl-32 cake-32">');
	writeln('		<dt>Today is ' . possessive_name(display_name($zid)) . ' Birthday</dt>');
	writeln('		<dd>' . $zid . '</dd>');
	writeln('	</dl>');
	writeln('</a>');

	$score = "5, Insightful";
	writeln('<a href="/comment/1">');
	writeln('	<dl class="dl-32 ' . score_icon($score) . '-32">');
	writeln('		<dt>Moderation to "This is a really long subject line that will probably need to be wrapped"</dt>');
	writeln('		<dd>Score: ' . $score . '</dd>');
	writeln('	</dl>');
	writeln('</a>');

	$zid = "bryan@pipedot.net";
	writeln('<a href="' . user_link($zid) . 'profile/">');
	writeln('	<dl class="dl-32 cake-32">');
	writeln('		<dt>Today is ' . possessive_name(display_name($zid)) . ' Birthday</dt>');
	writeln('		<dd>' . $zid . '</dd>');
	writeln('	</dl>');
	writeln('</a>');
*/

	writeln('</div>');
//	writeln('</div>');
	writeln('<div class="dialog-body center">');
//	writeln('<table class="side-link-two">');
//	writeln('	<tr>');
//	writeln('		<td><a class="bulb-32" href="' . user_link($auth_zid) . 'notification/">More (7)</a></td>');
//	writeln('		<td><a class="broom-32" href="' . user_link($auth_zid) . 'notification/clear">Clear</a></td>');
//	writeln('	</tr>');
//	writeln('</table>');
	writeln('<a class="icon-16 bulb-16" href="' . user_link($auth_zid) . 'notification/">More (' . $notification_count . ')</a> | <a class="icon-16 broom-16" href="' . user_link($auth_zid) . 'notification/clear">Clear</a>');
	writeln('</div>');
}


function notification_small($notification_id, $item_id, $parent_id, $type_id)
{
	if ($type_id == TYPE_COMMENT) {
		notification_comment_small($notification_id, $parent_id, $item_id);
	} else if ($type_id == TYPE_COMMENT_VOTE) {
		notification_moderation_small($notification_id, $item_id);
	} else if ($type_id == TYPE_JOURNAL) {
		notification_journal_small($notification_id, $parent_id, $item_id);
	} else if ($type_id == TYPE_MAIL) {
		notification_mail_small($notification_id, $parent_id, $item_id);
//	} else if ($type_id == TYPE_BIRTHDAY) {
//		notification_birthday_small($notification_id, $parent_id, $item_id);
	} else {
		fatal("Unknown notification");
	}
}


function notification_large($notification_id, $item_id, $parent_id, $type_id)
{
	if ($type_id == TYPE_COMMENT) {
		notification_comment_large($notification_id, $parent_id, $item_id);
	} else if ($type_id == TYPE_COMMENT_VOTE) {
		notification_moderation_large($notification_id, $item_id);
	} else if ($type_id == TYPE_JOURNAL) {
		notification_journal_large($notification_id, $item_id);
	} else if ($type_id == TYPE_MAIL) {
		notification_mail_large($notification_id, $parent_id, $item_id);
//	} else if ($type_id == TYPE_BIRTHDAY) {
//		notification_birthday_large($notification_id, $parent_id, $item_id);
	} else {
		fatal("Unknown notification");
	}
}


function notification_comment_small($notification_id, $parent_comment_id, $new_comment_id)
{
	global $auth_zid;

	$new_comment = db_get_rec("comment", $new_comment_id);
	$parent_comment = db_get_rec("comment", $parent_comment_id);
	//$link = item_link(TYPE_COMMENT, $new_comment_id, $new_comment);
	//$a = article_info($new_comment, false);
	if ($new_comment["zid"] == "") {
		$new_comment_zid = "Anonymous Coward";
	} else {
		$new_comment_zid = $new_comment["zid"];
	}

	writeln('<a href="' . user_link($auth_zid) . 'notification/' . $notification_id . '/view">');
	writeln('	<dl class="dl-32 chat-32">');
	writeln('		<dt>Reply to "' . $parent_comment["subject"] . '"</dt>');
	writeln('		<dd>' . $new_comment_zid . '</dd>');
	writeln('	</dl>');
	writeln('</a>');

//	print render_comment($new_comment["subject"], $new_comment["zid"], $new_comment["edit_time"], $new_comment["comment_id"], $new_comment["body"], 0, $a["link"], $a["title"], $new_comment["junk_status"]);
//	writeln('</div>');
//	writeln('</article>');
}


function notification_comment_large($notification_id, $parent_comment_id, $new_comment_id)
{
	$new_comment = db_get_rec("comment", $new_comment_id);
	$parent_comment = db_get_rec("comment", $parent_comment_id);
	$link = item_link(TYPE_COMMENT, $parent_comment_id, $parent_comment);
	//$a = article_info($new_comment, false);

	//writeln('<div class="notification-title icon-32 chat-32"><div>Reply to "asdf"</div><div><a class="icon-16 delete-16" href="dismiss">Dismiss</a></div></div>');

	//writeln('<div class="icon-16 chat-16">New comment in reply to "asdf"</div>');

	//writeln('<div class="notification-title icon-16 chat-16">Reply to "asdf"</div>');

	//writeln('<div class="notification-title2 chat-16 color-new">Reply to "asdf"</div>');
	writeln('<div class="notification-title2 color-new">New Comment</div>');
	writeln('<div class="notification-body">');

	//writeln('<a class="icon-16 news-16" href="' . $a["link"] . '">' . $a["title"] . '</a>');
//	writeln('<div style="margin-bottom: 8px">Reply to <a class="icon-16 chat-16" href="' . $a["link"] . '">' . $parent_comment["subject"] . '</a></div>');
//	writeln('<div style="margin-bottom: 8px"><a class="icon-16 chat-16" href="' . $a["link"] . '">Reply to "' . $parent_comment["subject"] . '"</a></div>');
//	writeln('<div style="margin-bottom: 8px" class="icon-16 chat-16">Reply to <a href="' . $a["link"] . '">"' . $parent_comment["subject"] . '"</a></div>');
	writeln('<div style="margin-bottom: 8px" class="icon-32 chat-32">Reply to <a href="' . $link . '">"' . $parent_comment["subject"] . '"</a></div>');

	//writeln('<div style="padding-left: 16px">');
	print_comment($new_comment, true);
//	print render_comment($new_comment["subject"], $new_comment["zid"], $new_comment["edit_time"], $new_comment["comment_id"], $new_comment["body"], 0, $a["link"], $a["title"], $new_comment["junk_status"]);
//	writeln('</div>');
//	writeln('</article>');
	//writeln('</div>');

	writeln('<div class="right" style="margin-top: 8px"><a class="icon-16 delete-16" href="' . $notification_id . '/dismiss">Dismiss</a></div>');
//	writeln('<div class="right" style="margin-top: 8px"><input type="submit" name="dismiss_' . $notification_id . '" value="Dismiss"></div>');
	writeln('</div>');
}


function notification_journal_small($notification_id, $journal_id)
{
	writeln('here');
	$journal = db_get_rec("journal", $journal_id);
	$link = item_link(TYPE_JOURNAL, $journal_id, $journal);

	writeln('<a href="' . $link . '">');
	writeln('	<dl class="dl-32 notepad-32">');
	writeln('		<dt>' . $journal["subject"] . '</dt>');
	writeln('		<dd>' . $journal["zid"] . '</dd>');
	writeln('	</dl>');
	writeln('</a>');
}


function notification_mail_small($notication_id, $mail_id)
{
	writeln('<a href="/comment/1">');
	writeln('	<dl class="dl-32 mail-32">');
	writeln('		<dt>Message "Hello World"</dt>');
	writeln('		<dd>zenbi@pipedot.net</dd>');
	writeln('	</dl>');
	writeln('</a>');
}

function notification_moderation_small($notification_id, $comment_id)
{
	global $auth_zid;

	$comment = db_get_rec("comment", $comment_id);

	list($score, $reason) = get_comment_score($comment_id);
	$score_reason = $score;
	if ($reason != "") {
		$score_reason .= ", $reason";
	}

	writeln('<a href="' . user_link($auth_zid) . 'notification/' . $notification_id . '/view">');
	writeln('	<dl class="dl-32 ' . score_icon($score_reason) . '-32">');
	writeln('		<dt>Moderation to "' . $comment["subject"] . '"</dt>');
	writeln('		<dd>Score: ' . $score_reason . '</dd>');
	writeln('	</dl>');
	writeln('</a>');
}


function notification_moderation_large($notification_id, $comment_id)
{
	$comment = db_get_rec("comment", $comment_id);
	//$link = item_link(TYPE_COMMENT, $comment_id, $comment);
	//$a = article_info($comment, false);

	list($score, $reason) = get_comment_score($comment_id);
	$score_reason = $score;
	if ($reason != "") {
		$score_reason .= ", $reason";
	}

	$icon = score_icon($score);

	writeln('<div class="notification-title2 color-new">Comment Moderation</div>');
	writeln('<div class="notification-body">');

//	writeln('<div style="margin-bottom: 8px" class="icon-16 face-smile-16">Score: 4, Insightful</div>');
	writeln('<div style="margin-bottom: 8px" class="icon-32 face-smile-32">Score: ' . $score_reason . '</div>');

	//writeln('<div style="padding-left: 16px">');
//	print render_comment($comment["subject"], $comment["zid"], $comment["edit_time"], $comment["comment_id"], $comment["body"], 0, $a["link"], $a["title"], $comment["junk_status"]);
//	writeln('</div>');
//	writeln('</article>');
	print_comment($comment, true);
	//writeln('</div>');

	//writeln('<div class="right" style="margin-top: 8px"><a class="icon-16 delete-16" href="dismiss">Dismiss</a></div>');
	//box_right("Dismiss");
	writeln('<div class="right" style="margin-top: 8px"><input type="submit" name="dismiss_' . $notification_id . '" value="Dismiss"></div>');
	writeln('</div>');
}


function notification_birthday_small($notification_id, $comment_id)
{
	$zid = "bryan@pipedot.net";
	writeln('<a href="' . user_link($zid) . 'profile/">');
	writeln('	<dl class="dl-32 cake-32">');
	writeln('		<dt>Today is ' . possessive_name(display_name($zid)) . ' Birthday</dt>');
	writeln('		<dd>' . $zid . '</dd>');
	writeln('	</dl>');
	writeln('</a>');
}
