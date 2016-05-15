<?
//
// Pipecode - distributed social network
// Copyright (C) 2014-2016 Bryan Beicker <bryan@pipedot.org>
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

if ($s2 === "edit") {
	$topic = db_new_rec("topic");
} else {
	if (!string_uses($s2, "[a-z]")) {
		fatal("Invalid topic");
	}
	$topic = $s2;
	$topic = db_get_rec("topic", array("slug" => $topic));
}

if (http_post("delete")) {
	$row = sql("select count(*) as stories from story where topic_id = ?", $topic["topic_id"]);
	$count = $row[0]["stories"];
	if ($count > 0) {
		fatal("Unable to delete topic - used in [$count] stories");
	}

	db_del_rec("topic", $topic["topic_id"]);
} else {
	$name = http_post_string("name", array("len" => 50, "valid" => "[a-z]"));
	$slug = http_post_string("slug", array("len" => 50, "valid" => "[a-z]"));
	$icon = http_post_string("icon", array("len" => 50, "valid" => "[a-z]-"));
	$promoted = http_post_bool("promoted", array("numeric" => true));

	$topic["topic"] = $name;
	$topic["slug"] = $slug;
	$topic["icon"] = $icon;
	$topic["promoted"] = $promoted;

	db_set_rec("topic", $topic);
}

header("Location: /topic/list");
