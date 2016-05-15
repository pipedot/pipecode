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

function make_bug_labels($bug_id)
{
	$labels = "";
	$row = sql("select label_name, label_tag, background_color, foreground_color from bug_labels inner join bug_label on bug_labels.label_id = bug_label.label_id where bug_id = ?", $bug_id);
	for ($i = 0; $i < count($row); $i++) {
		$labels .= '<a class="label" style="background-color: ' . $row[$i]["background_color"] . '; color: ' . $row[$i]["foreground_color"] . ';" href="' . $row[$i]["label_tag"] . '">' . $row[$i]["label_name"] . '</a>';
	}

	return $labels;
}


function print_bug_label_checkboxes($bug_id = 0)
{
	$labels = array();
	beg_tab("Labels");
	if ($bug_id == 0) {
		$list = db_get_list("bug_label", "label_name", array("reportable" => 1));
	} else {
		$row = sql("select label_id from bug_labels where bug_id = ?", $bug_id);
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
		return "bulb-32";
	} else if ($priority == "important") {
		return "important-32";
	} else if ($priority == "critical") {
		return "stop-32";
	} else {
		return "warning-32";
	}
}


function print_bug($bug)
{
	global $auth_user;
	global $auth_zid;

	$bug_id = $bug["bug_id"];
	$bug_code = crypt_crockford_encode($bug_id);

	$a["body"] = $bug["body"];
	$a["title"] = $bug["title"];
	$a["link"] = item_link(TYPE_BUG, $bug_id, $bug);
	$a["info"] = content_info($bug);
	$a["comments"] = count_comments($bug_id, TYPE_BUG);

	if ($auth_user["editor"] || $auth_user["admin"]) {
		$a["actions"][] = "<a href=\"/bug/$bug_code/edit\" class=\"icon-16 notepad-16\">" . get_text('Edit') . "</a>";
		if (!$bug["closed"]) {
			$a["actions"][] = "<a href=\"/bug/$bug_code/close\" class=\"icon-16 close-16\">" . get_text('Close') . "</a>";
		}
	}
	if ($auth_zid !== "" && $bug["closed"]) {
		$a["actions"][] = "<a href=\"/bug/$bug_code/open\" class=\"icon-16 undo-16\">" . get_text('Open') . "</a>";
	}

	print_content($a);
}
