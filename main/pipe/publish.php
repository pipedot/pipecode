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

$pid = $s2;
if (!string_uses($pid, "[0-9]")) {
	die("invalid pid [$pid]");
}

if (!$auth_user["editor"]) {
	die("you are not an editor");
}

$pipe = db_get_rec("pipe", $pid);
$zid = $pipe["author_zid"];

if (http_post()) {
	$title = clean_subject();
	list($clean_body, $dirty_body) = clean_body(true, "story");
	$icon = http_post_string("icon", array("len" => 50, "valid" => "[a-z][0-9]-_"));
	$tid = http_post_int("tid");
	$time = time();

	if (http_post("publish")) {
		$pipe = db_get_rec("pipe", $pid);
		if ($pipe["closed"] == 1) {
			die("pipe [$pid] is already closed");
		}
		$pipe["closed"] = 1;
		$pipe["edit_zid"] = $auth_zid;
		db_set_rec("pipe", $pipe);

		$story = array();
		$story["sid"] = 0;
		$story["author_zid"] = $pipe["author_zid"];
		$story["body"] = $clean_body;
		$story["edit_time"] = $time;
		$story["edit_zid"] = $auth_zid;
		$story["icon"] = $icon;
		$story["image_id"] = 0;
		$story["pid"] = $pid;
		$story["publish_time"] = $time;
		$story["slug"] = clean_url($title);
		$story["tid"] = $tid;
		$story["title"] = $title;
		$story["tweet_id"] = 0;
		db_set_rec("story", $story);

		header("Location: /pipe/$pid");
		die();
	}
} else {
	$title = $pipe["title"];
	$tid = $pipe["tid"];
	$icon = $pipe["icon"];
	$clean_body = $pipe["body"];
	$dirty_body = dirty_html($clean_body);
}

$topic = db_get_rec("topic", $tid);
$topic = $topic["topic"];

print_header("Publish Submission");

beg_form();
print_left_bar("main", "pipe");
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
//$icon_keys = array();
$a = fs_dir("$doc_root/www/images");
for ($i = 0; $i < count($a); $i++) {
	if (substr($a[$i], -7) == "-64.png") {
		$icon_list[] = substr($a[$i], 0, -7);
	}
}

writeln('<h1>Preview</h1>');
$a = array();
$a["title"] = $title;
$a["pid"] = $pid;
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
