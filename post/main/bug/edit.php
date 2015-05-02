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

$bug = item_request(TYPE_BUG);
$title = clean_subject();
$priority = http_post_string("priority", array("valid" => "[a-z]"));
$priorities = array("informational", "normal", "important", "critical");
if (!in_array($priority, $priorities)) {
	die("invalid priority [$pritority]");
}
$bug["priority"] = $priority;
$bug["title"] = $title;
db_set_rec("bug", $bug);

$labels = array();
$keys = array_keys($_POST);
//var_dump($keys);
for ($i = 0; $i < count($keys); $i++) {
	//print "checking [" . $keys[$i] . "]";
	if (substr($keys[$i], 0, 6) == "label_") {
		$label_id = (int) substr($keys[$i], 6);
		//print "label_id [$label_id]";
		if (db_has_rec("label", $label_id)) {
			$labels[] = $label_id;
		}
	}
}
//var_dump($labels);
//die("here");

sql("delete from bug_label where bug_id = ?", $bug["bug_id"]);
for ($i = 0; $i < count($labels); $i++) {
	//$bug_label = db_new_rec("bug_label");
	//$bug_label["bug_id"] = $bug["bug_id"];
	//$bug_label["label_id"] = $labels[$i];
	//db_set_rec("bug_label", $bug_label);
	sql("insert into bug_label (bug_id, label_id) values (?, ?)", $bug["bug_id"], $labels[$i]);
}

header("Location: /bug/" . crypt_crockford_encode($bug["bug_id"]));
