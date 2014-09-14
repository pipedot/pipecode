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

if ($auth_zid == "") {
	die("sign in to write");
}
if ($zid != $auth_zid) {
	die("not your journal");
}

print_header("Write");
beg_main();
beg_form();

writeln('<h1>Write</h1>');

beg_tab();
print_row(array("caption" => "Title", "text_key" => "title"));
print_row(array("caption" => "Topic", "text_key" => "topic"));
//print_row(array("caption" => "Icon", "option_key" => "icon", "option_list" => $icon_list));
//print_row(array("caption" => "Body", "textarea_key" => "body", "textarea_height" => "400"));
end_tab();

writeln('<div style="margin-bottom: 8px">');
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

right_box("Save");

end_form();
end_main();
print_footer();

