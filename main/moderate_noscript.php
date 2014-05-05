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

if (!http_post()) {
	die("error: post method required");
}

if ($auth_zid == "") {
	die("error: sign in to moderate");
}

function moderate($cid, $zid, $rid)
{
	if (!db_has_rec("comment", $cid)) {
		return;
	}
	if (db_has_rec("comment_vote", array("cid" => $cid, "zid" => $zid))) {
		$comment_vote = db_get_rec("comment_vote", array("cid" => $cid, "zid" => $zid));
		$old = $comment_vote["rid"];
		if ($rid == $old) {
			return;
		} else if ($rid == 0) {
			db_del_rec("comment_vote", array("cid" => $cid, "zid" => $zid));
		} else {
			$comment_vote["rid"] = $rid;
			db_set_rec("comment_vote", $comment_vote);
			return;
		}
	}
	$comment_vote = array();
	$comment_vote["cid"] = $cid;
	$comment_vote["zid"] = $zid;
	$comment_vote["rid"] = $rid;
	$comment_vote["time"] = time();
	db_set_rec("comment_vote", $comment_vote);
}

$k = array_keys($_POST);

for ($i = 0; $i < count($k); $i++) {
	$a = explode("_", $k[$i]);
	if (count($a) == 2) {
		if ($a[0] == "cid" && string_uses($a[1], "[0-9]") && string_uses($_POST[$k[$i]], "[0-9]-")) {
			$cid = (int) $a[1];
			$rid = (int) $_POST[$k[$i]];
			if ($rid >= 0 && $rid <= 10) {
				moderate($cid, $auth_zid, $rid);
			}
		}
	}
}

$sid = http_post_int("sid", array("required" => false));
$pid = http_post_int("pid", array("required" => false));
$qid = http_post_int("qid", array("required" => false));

if ($sid > 0) {
	header("Location: /story/$sid");
} else if ($pid > 0) {
	header("Location: /story/$sid");
} else if ($qid > 0) {
	header("Location: /poll/$qid");
} else {
	header("Location: /");
}
