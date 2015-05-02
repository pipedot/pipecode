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

if ($auth_zid === "") {
	die("error: sign in to moderate");
}

$comment = item_request(TYPE_COMMENT);

if (db_has_rec("comment_vote", array("comment_id" => $comment["comment_id"], "zid" => $auth_zid))) {
	db_del_rec("comment_vote", array("comment_id" => $comment["comment_id"], "zid" => $auth_zid));
}

$reason = http_post_string("reason", array("len" => 20, "valid" => "[a-z][A-Z]"));

if (!array_key_exists($reason, $reasons)) {
	die("error: unknown reason [$reason]");
}
$value = $reasons[$reason];

if ($reason != "Normal") {
	$comment_vote = db_new_rec("comment_vote");
	$comment_vote["comment_id"] = $comment["comment_id"];
	$comment_vote["zid"] = $auth_zid;
	$comment_vote["reason"] = $reason;
	$comment_vote["time"] = time();
	$comment_vote["value"] = $value;
	db_set_rec("comment_vote", $comment_vote);
}

list($score, $reason) = get_comment_score($comment["comment_id"]);

writeln('{');
writeln('	"code": "' . $comment["short_code"] . '",');
writeln('	"score": ' . $score . ',');
writeln('	"reason": "' . $reason . '"');
writeln('}');
