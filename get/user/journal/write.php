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

require_mine();

$spinner[] = ["name" => "Journal", "link" => "/journal/"];
$spinner[] = ["name" => "Write", "link" => "/journal/write"];

print_header(["form" => true]);

beg_tab();
print_row(array("caption" => "Title", "text_key" => "title"));
print_row(array("caption" => "Topic", "text_key" => "topic"));
//print_row(array("caption" => "Icon", "option_key" => "icon", "option_list" => $icon_list));
//print_row(array("caption" => "Body", "textarea_key" => "body", "textarea_height" => "400"));
end_tab();

writeln('<div class="box">');
writeln('<textarea name="body" style="width: 100%; height: 100px"></textarea>');
writeln('</div>');

if ($auth_user["javascript_enabled"] && $auth_user["wysiwyg_enabled"]) {
	writeln('<script type="text/javascript" src="/lib/ckeditor/ckeditor.js"></script>');
	writeln('<script type="text/javascript">');
	writeln();
	writeln('CKEDITOR.replace("body",');
	writeln('{');
	writeln('	resize_enabled: false,');
	writeln('	enterMode: CKEDITOR.ENTER_BR,');
	writeln('	toolbar :');
	writeln('	[');
	writeln('		["Bold","Italic","Underline","Strike"],');
	writeln('		["NumberedList","BulletedList","Blockquote"],');
	writeln('		["Link","Unlink"],');
	writeln('		["Image"]');
	writeln('	]');
	writeln('});');
	writeln();
	writeln('</script>');
}

box_right("Save");

print_footer(["form" => true]);
