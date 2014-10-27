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
include("clean.php");

if ($auth_zid === "") {
	die("sign in to edit");
}

$subject = clean_subject();
list($clean_body, $dirty_body) = clean_body();

$comment = find_rec("comment");

if ($comment["zid"] !== $auth_zid) {
	die("not your comment");
}

if ($comment["body"] !== $clean_body || $comment["subject"] !== $subject) {
	db_set_rec("comment_edit", $comment);

	$comment["body"] = $clean_body;
	$comment["edit_time"] = time();
	$comment["subject"] = $subject;
	db_set_rec("comment", $comment);

	sql("delete from comment_vote where comment_id = ? and value > 0", $comment["comment_id"]);
}

header("Location: " . item_link($comment["type"], $comment["root_id"]));
