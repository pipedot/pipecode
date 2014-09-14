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

include("clean.php");

if ($auth_zid == "") {
	die("sign in to write");
}
if ($zid != $auth_zid) {
	die("not your journal");
}

$title = clean_subject();
$topic = clean_topic();
list($clean_body, $dirty_body) = clean_body(false, "journal");
$time = time();

$journal = array();
$journal["journal_id"] = create_id($auth_zid, $time);
$journal["body"] = $clean_body;
$journal["edit_time"] = $time;
$journal["photo_id"] = 0;
$journal["publish_time"] = 0;
$journal["published"] = 0;
$journal["short_id"] = create_short("journal", $journal["journal_id"]);
$journal["slug"] = clean_url($title);
$journal["title"] = $title;
$journal["topic"] = $topic;
$journal["zid"] = $auth_zid;
db_set_rec("journal", $journal);

header("Location: /journal/" . crypt_crockford_encode($journal["short_id"]));
