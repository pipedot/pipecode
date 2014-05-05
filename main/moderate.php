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

$cid = (int) $s2;

if (!http_post()) {
	die("error: post method required");
}

if ($auth_zid == "") {
	die("error: sign in to moderate");
}

if (!db_has_rec("comment", $cid)) {
	die("error: comment not found [$cid]");
}

if (db_has_rec("comment_vote", array("cid" => $cid, "zid" => $auth_zid))) {
	db_del_rec("comment_vote", array("cid" => $cid, "zid" => $auth_zid));
}

$rid = http_post_int("rid");

if ($rid > 0 && $rid <= 10) {
	$comment_vote = array();
	$comment_vote["cid"] = $cid;
	$comment_vote["zid"] = $auth_zid;
	$comment_vote["rid"] = $rid;
	$comment_vote["time"] = time();
	db_set_rec("comment_vote", $comment_vote);
}

$score = get_comment_score($cid);

writeln("$cid $score");
