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

if (!$auth_user["admin"]) {
	die("not an admin");
}

$slug = http_get_string("slug", array("len" => 100, "valid" => "[a-z][A-Z][0-9]-_."));
$page = db_get_rec("page", $slug);

if (http_post()) {
	$title = http_post_string("title", array("len" => 100, "valid" => "[ALL]"));
	$body = http_post_string("body", array("len" => 64000, "valid" => "[ALL]"));

	$title = clean_unicode($title);
	$title = clean_entities($title);
	//$body = str_replace("\n", "<br>", $body);
	$body = clean_html($body);

	$page = array();
	$page["slug"] = $slug;
	$page["title"] = $title;
	$page["body"] = $body;

	db_set_rec("page", $page);
	header("Location: ./");
	die();
}

print_header("Edit Page");

beg_form();

beg_tab("Edit Page");
print_row(array("caption" => "Slug", "text_key" => "slug", "text_value" => $page["slug"]));
print_row(array("caption" => "Title", "text_key" => "title", "text_value" => $page["title"]));
end_tab();

writeln('<textarea name="body" style="width: 100%; height: 600px; margin-bottom: 8px;">' . $page["body"] . '</textarea>');
writeln('<script type="text/javascript" src="/lib/ckeditor/ckeditor.js"></script>');
writeln('<script type="text/javascript">');
writeln();
writeln('CKEDITOR.replace("body",');
writeln('{');
writeln('	resize_enabled: false,');
writeln('	toolbar :');
writeln('	[');
writeln('		["Bold","Italic","Underline","Strike"],');
writeln('		["NumberedList","BulletedList","Blockquote"],');
writeln('		["Link","Unlink"]');
//writeln('		["Format"]');
writeln('	]');
writeln('});');
/*
writeln('CKEDITOR.replace("body",');
writeln('{');
//writeln('	width: 320,');
writeln('	height: 540,');
writeln('	resize_enabled: false,');
writeln('	toolbarCanCollapse: false,');
writeln('	extraPlugins : "uicolor",');
writeln('	uiColor: "#eeeeee",');
writeln('	toolbar :');
writeln('	[');
writeln('		["Styles","RemoveFormat","-","Bold","Italic","Underline","-","JustifyLeft","JustifyCenter","JustifyRight","-","NumberedList","BulletedList","-","Link","Table","Image","-","View HTML"]');
writeln('	]');
writeln('});');
*/
writeln();
writeln('</script>');

right_box("Save");
end_form();

print_footer();
