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

require_admin();

if (!string_uses($s2, "[a-z][0-9]-", 100)) {
	fatal("Invalid slug");
}
$slug = $s2;

$page = db_get_rec("page", $slug);

$spinner[] = ["name" => "Page", "link" => "/page/"];
$spinner[] = ["name" => $page["title"], "link" => "/$slug"];
$spinner[] = ["name" => "Edit", "link" => "/page/$slug/edit"];

print_header(["title" => "Edit Page", "form" => true]);

beg_tab("Edit Page");
print_row(array("caption" => "Title", "text_key" => "title", "text_value" => $page["title"]));
print_row(array("caption" => "Slug", "text_key" => "slug", "text_value" => $page["slug"]));
end_tab();

writeln('<div class="box">');
writeln('<textarea name="body" style="width: 100%; height: 600px;">' . $page["body"] . '</textarea>');
writeln('</div>');
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
writeln('		["Link","Unlink"],');
writeln('		["Format"]');
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

box_right("Save");

print_footer(["form" => true]);
