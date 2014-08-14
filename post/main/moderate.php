<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
//
// This file is part of Pipecode.
//
// Pipecode is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Pipecode is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Pipecode.  If not, see <http://www.gnu.org/licenses/>.
//

include("render.php");

$comment_id = $s2;
if (!string_uses($comment_id, "[a-z][0-9]_")) {
	die("invalid comment_id [$comment_id]");
}
if ($auth_zid == "") {
	die("error: sign in to moderate");
}

if (!db_has_rec("comment", $comment_id)) {
	die("error: comment not found [$comment_id]");
}

if (db_has_rec("comment_vote", array("comment_id" => $comment_id, "zid" => $auth_zid))) {
	db_del_rec("comment_vote", array("comment_id" => $comment_id, "zid" => $auth_zid));
}

$reason = http_post_string("reason", array("len" => 20, "valid" => "[a-z][A-Z]"));

$keys = array_keys($reasons);
if (!in_array($reason, $keys)) {
	die("unknown reason [$reason]");
}
$value = $reasons[$reason];

if ($reason != "Normal") {
	$comment_vote = array();
	$comment_vote["comment_id"] = $comment_id;
	$comment_vote["zid"] = $auth_zid;
	$comment_vote["reason"] = $reason;
	$comment_vote["time"] = time();
	$comment_vote["value"] = $value;
	db_set_rec("comment_vote", $comment_vote);
}

list($score, $reason) = get_comment_score($comment_id);

writeln("$comment_id $score $reason");
