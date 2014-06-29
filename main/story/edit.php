<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
//
// This file is part of Pipecode.
//
// Pipecode is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Pipecode is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Pipecode.  If not, see <http://www.gnu.org/licenses/>.
//

include("clean.php");
include("story.php");

if (!@$auth_user["editor"]) {
	die("you are not an editor");
}

$sid = (int) $s2;
$story = db_get_rec("story", $sid);
$pipe = db_get_rec("pipe", $story["pid"]);
$zid = $pipe["zid"];

if (http_post()) {
	$title = clean_subject();
	list($clean_body, $dirty_body) = clean_body(true, "story");
	$icon = http_post_string("icon", array("len" => 50, "valid" => "[a-z][0-9]-_"));
	$tid = http_post_int("tid");

	if (http_post("publish")) {
		$story["tid"] = $tid;
		$story["title"] = $title;
		$story["ctitle"] = clean_url($title);
		$story["icon"] = $icon;
		$story["story"] = $clean_body;
		db_set_rec("story", $story);

		header("Location: /story/$sid");
		die();
	}
} else {
	$title = $story["title"];
	$tid = $story["tid"];
	$icon = $story["icon"];
	$clean_body = $story["story"];
	$dirty_body = dirty_html($clean_body);
}

$topic = db_get_rec("topic", $tid);
$topic = $topic["topic"];

print_header();

print_left_bar("main", "stories");
beg_main("cell");

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

beg_form();
writeln('<h1>Preview</h1>');
$a = array();
$a["title"] = $title;
$a["time"] = $story["time"];
$a["pid"] = $story["pid"];
$a["zid"] = $zid;
$a["topic"] = $topic;
$a["icon"] = $icon;
$a["story"] = $clean_body;
print_article($a);

writeln('<h1>Edit</h1>');
beg_tab();
print_row(array("caption" => "Title", "text_key" => "title", "text_value" => $title));
print_row(array("caption" => "Topic", "option_key" => "tid", "option_value" => $tid, "option_list" => $topic_list, "option_keys" => $topic_keys));
print_row(array("caption" => "Icon", "option_key" => "icon", "option_value" => $icon, "option_list" => $icon_list));
print_row(array("caption" => "Story", "textarea_key" => "story", "textarea_value" => $dirty_body, "textarea_height" => "400"));
end_tab();

writeln('<table class="fill" style="margin-bottom: 8px">');
writeln('	<tr>');
writeln('		<td><a href="/icons">Icons</a></td>');
writeln('		<td style="text-align: right"><input name="publish" type="submit" value="Publish"/> <input name="preview" type="submit" value="Preview"/></td>');
writeln('	</tr>');
writeln('</table>');
//right_box("Publish,Preview");

end_form();
end_main();
print_footer();
