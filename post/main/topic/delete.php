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

require_admin();

if (!string_uses($s2, "[a-z]")) {
	fatal("Invalid topic");
}
$topic = $s2;
$topic = db_get_rec("topic", array("slug" => $topic));

$row = sql("select count(*) as stories from story where topic_id = ?", $topic["topic_id"]);
$count = $row["stories"];

die("count [$count]");
//db_set_rec("topic", $topic);

header("Location: /topic/list");
