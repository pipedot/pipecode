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

function make_bug_labels($bug_short_id)
{
	$labels = "";
	$row = sql("select label_name, label_tag, background_color, foreground_color from bug_labels inner join bug_label on bug_labels.label_id = bug_label.label_id where bug_short_id = ?", $bug_short_id);
	for ($i = 0; $i < count($row); $i++) {
		$labels .= '<a class="label" style="background-color: ' . $row[$i]["background_color"] . '; color: ' . $row[$i]["foreground_color"] . ';" href="' . $row[$i]["label_tag"] . '">' . $row[$i]["label_name"] . '</a>';
	}

	return $labels;
}


function print_bug_label_checkboxes($bug_short_id = 0)
{
	$labels = array();
	beg_tab("Labels");
	if ($bug_short_id == 0) {
		$list = db_get_list("bug_label", "label_name", array("reportable" => 1));
	} else {
		$row = sql("select label_id from bug_labels where bug_short_id = ?", $bug_short_id);
		for ($i = 0; $i < count($row); $i++) {
			$labels[] = $row[$i]["label_id"];
		}

		$list = db_get_list("bug_label", "label_name");
	}
	$keys = array_keys($list);
	for ($i = 0; $i < count($list); $i++) {
		$item = $list[$keys[$i]];
		print_row(array("caption" => '<span class="label" style="background-color: ' . $item["background_color"] . '; color: ' . $item["foreground_color"] . ';">' . $item["label_name"] . '</span>', "check_key" => "label_" . $item["label_id"], "checked" => in_array($item["label_id"], $labels)));
	}
	end_tab();
}


function bug_priority_icon($priority)
{
	if ($priority == "informational") {
		return "bulb_32";
	} else if ($priority == "important") {
		return "important_32";
	} else if ($priority == "critical") {
		return "stop_32";
	} else {
		return "warning_32";
	}
}


function print_bug($bug)
{
	global $auth_zid;

	$a["body"] = $bug["body"];
	$a["short_id"] = $bug["short_id"];
	$a["bug_id"] = $bug["bug_id"];
	$a["time"] = $bug["publish_time"];
	$a["title"] = $bug["title"];
	$a["zid"] = $bug["author_zid"];
	$a["labels"] = make_bug_labels($bug["short_id"]);
	$a["closed"] = $bug["closed"];
	$a["comments"] = count_comments("bug", $bug["long_id"]);

	print_article($a);
}
