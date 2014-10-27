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

function print_publish_box($pipe_id, $tid, $icon, $title, $clean_body, $dirty_body, $zid)
{
	global $doc_root;

	$topic = db_get_rec("topic", $tid);
	$topic = $topic["topic"];

	print_header("Publish Submission");
	print_left_bar("main", "pipe");
	beg_main("cell");
	beg_form();

	$topic_list = array();
	$topic_keys = array();
	$topics = db_get_list("topic", "topic");
	$k = array_keys($topics);
	for ($i = 0; $i < count($topics); $i++) {
		$topic_list[] = $topics[$k[$i]]["topic"];
		$topic_keys[] = $k[$i];
	}

	$icon_list = array();
	$a = fs_dir("$doc_root/www/images");
	for ($i = 0; $i < count($a); $i++) {
		if (substr($a[$i], -7) == "-64.png") {
			$icon_list[] = substr($a[$i], 0, -7);
		}
	}

	writeln('<h1>Preview</h1>');
	$a = array();
	$a["title"] = $title;
	$a["pipe_id"] = $pipe_id;
	$a["zid"] = $zid;
	$a["topic"] = $topic;
	$a["icon"] = $icon;
	$a["body"] = $clean_body;
	print_article($a);

	writeln('<h1>Publish</h1>');
	beg_tab();
	print_row(array("caption" => "Title", "text_key" => "title", "text_value" => $title));
	print_row(array("caption" => "Topic", "option_key" => "tid", "option_value" => $tid, "option_list" => $topic_list, "option_keys" => $topic_keys));
	print_row(array("caption" => "Icon", "option_key" => "icon", "option_value" => $icon, "option_list" => $icon_list));
	print_row(array("caption" => "Story", "textarea_key" => "story", "textarea_value" => $dirty_body, "textarea_height" => "400"));
	end_tab();

	left_right_box('<a href="/icons">Icons</a>', "Publish,Preview");

	end_form();
	end_main();
	print_footer();
}
