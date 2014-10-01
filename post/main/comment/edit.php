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
include("clean.php");

if ($auth_zid === "") {
	die("sign in to edit");
}

if (string_uses($s2, "[A-Z][a-z][0-9]")) {
	$short_id = crypt_crockford_decode($s2);
	$short = db_get_rec("short", $short_id);
	if ($short["type"] != "comment") {
		die("invalid short code [$s2]");
	}
	$comment_id = $short["item_id"];
} else if (string_uses($s2, "[a-z][0-9]_")) {
	$comment_id = $s2;
} else {
	die("invalid request");
}

$subject = clean_subject();
list($clean_body, $dirty_body) = clean_body();

$comment = db_get_rec("comment", $comment_id);

if ($comment["zid"] != $auth_zid) {
	die("not your comment");
}

db_set_rec("comment_edit", $comment);

$comment["body"] = $clean_body;
$comment["edit_time"] = time();
$comment["subject"] = $subject;
db_set_rec("comment", $comment);

sql("delete from comment_vote where comment_id = ? and value > 0", $comment["comment_id"]);
//die("clean body [$clean_body]");

header("Location: " . item_link($comment["type"], $comment["root_id"]));
