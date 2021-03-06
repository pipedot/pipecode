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

require_feature("bug");
require_developer();

$bug = item_request(TYPE_BUG);

$labels = array();
$row = sql("select label_id from bug_labels where bug_id = ?", $bug["bug_id"]);
for ($i = 0; $i < count($row); $i++) {
	$labels[] = $row[$i]["label_id"];
}

$spinner[] = ["name" => "Bug", "link" => "/bug/"];
$spinner[] = ["name" => "Edit Bug", "link" => "/bug/edit"];
$actions[] = ["name" => "Report", "icon" => "ladybug", "link" => "/bug/report"];

print_header(["form" => true]);

writeln('<h1>Edit Bug</h1>');

beg_tab();
print_row(array("caption" => "Title", "text_key" => "title", "required" => true, "text_value" => $bug["title"]));
print_row(array("caption" => "Priority", "option_key" => "priority", "option_list" => array("Informational", "Normal", "Important", "Critical"), "option_keys" => array("informational", "normal", "important", "critical"), "option_value" => $bug["priority"]));
if ($auth_zid === "") {
	print_row(array("caption" => "Captcha", "text_key" => "captcha"));
}
end_tab();

beg_tab("Labels");
$list = db_get_list("bug_label", "label_name");
$keys = array_keys($list);
for ($i = 0; $i < count($list); $i++) {
	$item = $list[$keys[$i]];
	print_row(array("caption" => '<span class="label" style="background-color: ' . $item["background_color"] . '; color: ' . $item["foreground_color"] . ';">' . $item["label_name"] . '</span>', "check_key" => "label_" . $item["label_id"], "checked" => in_array($item["label_id"], $labels)));
}
end_tab();

box_right("Save");

print_footer(["form" => true]);
