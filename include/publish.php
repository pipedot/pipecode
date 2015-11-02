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

function print_publish_box($pipe_id, $topic_id, $keywords, $title, $clean_body, $dirty_body, $zid)
{
	global $doc_root;

	$pipe = db_get_rec("pipe", $pipe_id);
	$topic = db_get_rec("topic", $topic_id);

	print_header("Publish Submission");
	print_main_nav("pipe");
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

	writeln('<h1>' . get_text('Preview') . '</h1>');
	$a["body"] = $clean_body;
	$a["title"] = $title;
	$a["link"] = item_link(TYPE_PIPE, $pipe_id, $pipe);
	$a["info"] = content_info($pipe, $topic);
	$a["view"] = "<b>0</b> comments";
	print_content($a);

	writeln('<h1>' . get_text('Publish') . '</h1>');
	beg_tab();
	print_row(array("caption" => "Title", "text_key" => "title", "text_value" => $title));
	print_row(array("caption" => "Topic", "option_key" => "topic_id", "option_value" => $topic_id, "option_list" => $topic_list, "option_keys" => $topic_keys));
	print_row(array("caption" => "Keywords", "text_key" => "keywords", "text_value" => $keywords));
	//print_row(array("caption" => "Icon", "option_key" => "icon", "option_value" => $icon, "option_list" => $icon_list));
	print_row(array("caption" => "Story", "textarea_key" => "story", "textarea_value" => $dirty_body, "textarea_height" => "400"));
	end_tab();

	//box_two('<a href="/icons">Icons</a>', "Publish,Preview");
	box_two('<a href="/similar">' . get_text('Keyword Search') . '</a>', "Publish,Preview");

	end_form();
	end_main();
	print_footer();
}
