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

if ($auth_zid === "") {
	die("error: sign in to moderate");
}

$article_code = http_post_string("article_code", array("valid" => "[A-Z][0-9]"));
$article_id = crypt_crockford_decode($article_code);


function moderate($comment_id, $zid, $reason)
{
	global $reasons;

	if (!array_key_exists($reason, $reasons)) {
		return;
	}
	$comment = db_find_rec("comment", $comment_id);
	if (!$comment) {
		return;
	}
	if (db_has_rec("comment_vote", array("comment_id" => $comment_id, "zid" => $zid))) {
		$comment_vote = db_get_rec("comment_vote", array("comment_id" => $comment_id, "zid" => $zid));
		$old = $comment_vote["reason"];
		if ($reason == $old) {
			return;
		} else if ($reason == "Normal") {
			db_del_rec("comment_vote", array("comment_id" => $comment_id, "zid" => $zid));
		} else {
			$comment_vote["reason"] = $reason;
			$comment_vote["value"] = $reasons[$reason];
			$comment_vote["time"] = time();
			db_set_rec("comment_vote", $comment_vote);
			send_notification_moderation($comment_id, $comment["zid"]);
			return;
		}
	}
	if ($reason == "Normal") {
		send_notification_moderation($comment_id, $comment["zid"]);
		return;
	}
	$comment_vote = db_new_rec("comment_vote");
	$comment_vote["comment_id"] = $comment_id;
	$comment_vote["reason"] = $reason;
	$comment_vote["time"] = time();
	$comment_vote["value"] = $reasons[$reason];
	$comment_vote["zid"] = $zid;
	db_set_rec("comment_vote", $comment_vote);
	send_notification_moderation($comment_id, $comment["zid"]);
}


$k = array_keys($_POST);
for ($i = 0; $i < count($k); $i++) {
	if (substr($k[$i], 0, 8) == "comment_") {
		$comment_code = substr($k[$i], 8);
		if (!string_uses($comment_code, "[A-Z][0-9]")) {
			die("invalid comment code");
		}
		$comment_id = crypt_crockford_decode($comment_code);
		$reason = $_POST[$k[$i]];
		if (!array_key_exists($reason, $reasons)) {
			die("invaid reason");
		}

		moderate($comment_id, $auth_zid, $reason);
	}
}

item_redirect("", $article_id);
