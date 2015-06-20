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

if (!string_uses($s3, "[a-z][0-9]-")) {
	fatal("Invalid topic");
}
$feed_topic = db_get_rec("feed_topic", ["slug" => $s3]);

db_del_rec("feed_topic", $feed_topic["topic_id"]);
sql("update feed set topic_id = 0 where topic_id = ?", $feed_topic["topic_id"]);

header("Location: /feed/topic/list");
