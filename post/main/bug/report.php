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

$title = clean_subject();
list($clean_body, $dirty_body) = clean_body(false, "comment");

$priority = http_post_string("priority", array("valid" => "[a-z]"));
$priorities = array("informational", "normal", "important", "critical");
if (!in_array($priority, $priorities)) {
	die("invalid priority [$pritority]");
}

$labels = array();
for ($i = 0; $i < count($_POST); $i++) {
	if (substr($_POST[$i], 0, 6) == "label_") {
		$label_id = (int) substr($_POST[$i], 6);
		if (!in_array($label_id, $labels) && db_has_rec("label", $label_id)) {
			$labels[] = $label_id;
		}
	}
}

$bug = db_new_rec("bug");
$bug["bug_id"] = create_short("bug");
$bug["author_zid"] = $auth_zid;
$bug["body"] = $clean_body;
$bug["priority"] = $priority;
$bug["title"] = $title;
db_set_rec("bug", $bug);

for ($i = 0; $i < count($labels); $i++) {
	$bug_label = db_new_rec("bug_label");
	$bug_label["bug_id"] = $bug["bug_id"];
	$bug_label["label_id"] = $labels[$i];
	db_set_rec("bug_label");
}

header("Location: /bug/" . crypt_crockford_encode($bug["bug_id"]));
