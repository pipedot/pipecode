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

require_editor();

$comment = item_request(TYPE_COMMENT);
if ($_POST["junk"] == "not-junk") {
	$comment["junk_status"] = -1;
} else if ($_POST["junk"] == "spam") {
	$comment["junk_status"] = 1;
} else {
	$comment["junk_status"] = 0;
}
$comment["junk_time"] = $now;
$comment["junk_zid"] = $auth_zid;
db_set_rec("comment", $comment);

recount_comments($comment["article_id"]);

header("Location: /comment/" . $comment["short_code"]);
