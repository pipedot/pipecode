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

	$row = sql("select notification_id, item_id, parent_id, type_id from notification where zid = ? order by time desc limit 5", $auth_zid);
	for ($i = 0; $i < count($row); $i++) {
		print_notification_row($row[$i]["notification_id"], $row[$i]["item_id"], $row[$i]["parent_id"], $row[$i]["type_id"], false);
	}

	writeln('</div>');
	writeln('<div class="dialog-body center">');
	writeln('<a class="icon-16 bulb-16" href="' . user_link($auth_zid) . 'notification/">More (' . $notification_count . ')</a> | <a class="icon-16 broom-16" href="' . user_link($auth_zid) . 'notification/clear">Clear</a>');
	writeln('</div>');
}


function print_notification_row($notification_id, $item_id, $parent_id, $type_id, $large)
{
	if ($type_id == TYPE_COMMENT) {
		print_notification_comment($notification_id, $parent_id, $item_id, $large);
	} else if ($type_id == TYPE_COMMENT_VOTE) {
		print_notification_moderation($notification_id, $item_id, $large);
	} else if ($type_id == TYPE_JOURNAL) {
		print_notification_journal($notification_id, $parent_id, $item_id, $large);
	} else if ($type_id == TYPE_MAIL) {
		print_notification_mail_small($notification_id, $parent_id, $item_id, $large);
//	} else if ($type_id == TYPE_BIRTHDAY) {
//		print_notification_birthday($notification_id, $parent_id, $item_id, $large);
	} else {
		fatal("Unknown notification");
	}
}


function print_notification_comment($notification_id, $parent_id, $new_comment_id, $large)
{
	global $auth_zid;

	$short = db_get_rec("short", $parent_id);
	$parent_type_id = $short["type_id"];
	$parent_type = item_type($parent_type_id);
	$parent = db_get_rec($parent_type, $parent_id);
	$link = item_link($parent_type_id, $parent_id, $parent);
	if ($parent_type_id == TYPE_JOURNAL) {
		$parent_title = $parent["title"];
		$icon = "notepad";
	} else {
		$parent_title = $parent["subject"];
		$icon = "chat";
	}

	$new_comment = db_get_rec("comment", $new_comment_id);
	if ($new_comment["zid"] == "") {
		$new_comment_zid = "Anonymous Coward";
	} else {
		$new_comment_zid = $new_comment["zid"];
	}

	if ($large) {
		writeln('<div class="notification-title2 color-new">New Comment</div>');
		writeln('<div class="notification-body">');
		writeln('<div style="margin-bottom: 8px" class="icon-32 ' . $icon . '-32">Reply to <a href="' . $link . '">"' . $parent_title . '"</a></div>');
		print_comment($new_comment, true);
		writeln('<div class="right" style="margin-top: 8px"><a class="icon-16 delete-16" href="' . $notification_id . '/dismiss">Dismiss</a></div>');
		writeln('</div>');
	} else {
		writeln('<a href="' . user_link($auth_zid) . 'notification/' . $notification_id . '">');
		writeln('	<dl class="dl-32 ' . $icon . '-32">');
		writeln('		<dt>Reply to "' . $parent_title . '"</dt>');
		writeln('		<dd>' . $new_comment_zid . '</dd>');
		writeln('	</dl>');
		writeln('</a>');
	}
}


function print_notification_journal($notification_id, $journal_id, $large)
{
	$journal = db_get_rec("journal", $journal_id);
	$link = item_link(TYPE_JOURNAL, $journal_id, $journal);

	writeln('<a href="' . $link . '">');
	writeln('	<dl class="dl-32 notepad-32">');
	writeln('		<dt>' . $journal["subject"] . '</dt>');
	writeln('		<dd>' . $journal["zid"] . '</dd>');
	writeln('	</dl>');
	writeln('</a>');
}


function print_notification_mail($notication_id, $mail_id, $large)
{
	writeln('<a href="/comment/1">');
	writeln('	<dl class="dl-32 mail-32">');
	writeln('		<dt>Message "Hello World"</dt>');
	writeln('		<dd>zenbi@pipedot.net</dd>');
	writeln('	</dl>');
	writeln('</a>');
}


function print_notification_moderation($notification_id, $comment_id, $large)
{
	global $auth_zid;

	$comment = db_get_rec("comment", $comment_id);
	list($score, $reason) = get_comment_score($comment_id);
	$score_reason = $score;
	if ($reason != "") {
		$score_reason .= ", $reason";
	}
	$icon = score_icon($score);

	if ($large) {
		writeln('<div class="notification-title2 color-new">Comment Moderation</div>');
		writeln('<div class="notification-body">');
		writeln('<div style="margin-bottom: 8px" class="icon-32 face-smile-32">Score: ' . $score_reason . '</div>');
		print_comment($comment, true);
		writeln('<div class="right" style="margin-top: 8px"><a class="icon-16 delete-16" href="' . $notification_id . '/dismiss">Dismiss</a></div>');
		//writeln('<div class="right" style="margin-top: 8px"><input type="submit" name="dismiss_' . $notification_id . '" value="Dismiss"></div>');
		writeln('</div>');
	} else {
		writeln('<a href="' . user_link($auth_zid) . 'notification/' . $notification_id . '">');
		writeln('	<dl class="dl-32 ' . score_icon($score_reason) . '-32">');
		writeln('		<dt>Moderation to "' . $comment["subject"] . '"</dt>');
		writeln('		<dd>Score: ' . $score_reason . '</dd>');
		writeln('	</dl>');
		writeln('</a>');
	}
}


function notification_birthday($notification_id, $comment_id, $large)
{
	$zid = "bryan@pipedot.net";
	writeln('<a href="' . user_link($zid) . 'summary">');
	writeln('	<dl class="dl-32 cake-32">');
	writeln('		<dt>Today is ' . possessive_name(display_name($zid)) . ' Birthday</dt>');
	writeln('		<dd>' . $zid . '</dd>');
	writeln('	</dl>');
	writeln('</a>');
}


function send_notification_comment($comment_id, $parent_id, $zid)
{
	$notification = db_new_rec("notification");
	$notification["item_id"] = $comment_id;
	$notification["parent_id"] = $parent_id;
	$notification["type_id"] = TYPE_COMMENT;
	$notification["zid"] = $zid;
	db_set_rec("notification", $notification);
}


function send_notification_moderation($comment_id, $zid)
{
	if ($zid == "") {
		return;
	}

	$notification = db_find_rec("notification", ["item_id" => $comment_id, "type_id" => TYPE_COMMENT_VOTE, "zid" => $zid]);
	if ($notification) {
		$notification["time"] = time();
	} else {
		$notification = db_new_rec("notification");
		$notification["item_id"] = $comment_id;
		$notification["type_id"] = TYPE_COMMENT_VOTE;
		$notification["zid"] = $zid;
	}
	db_set_rec("notification", $notification);
}
