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

include("clean.php");

if ($zid !== $auth_zid) {
	die("not your journal");
}

$journal = item_request("journal");
$title = clean_subject();
$topic = clean_topic();
list($clean_body, $dirty_body) = clean_body(false, "journal");
$time = time();

$journal["body"] = $clean_body;
$journal["edit_time"] = $time;
$journal["slug"] = clean_url($title);
$journal["title"] = $title;
$journal["topic"] = $topic;
db_set_rec("journal", $journal);

if ($journal["published"]) {
	header("Location: /journal/" . gmdate("Y-m-d", $journal["publish_time"]) . "/" . $journal["slug"]);
} else {
	header("Location: /journal/{$journal["short_code"]}");
}
