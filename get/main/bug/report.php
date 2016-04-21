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

require_feature("bug");
require_login();

$spinner[] = ["name" => "Bug", "link" => "/bug/"];
$spinner[] = ["name" => "Report Bug", "link" => "/bug/report"];

print_header(["form" => true]);

writeln('<h1>Report Bug</h1>');

beg_tab();
print_row(array("caption" => "Title", "text_key" => "title", "required" => true));
print_row(array("caption" => "Priority", "option_key" => "priority", "option_list" => array("Informational", "Normal", "Important", "Critical"), "option_keys" => array("informational", "normal", "important", "critical"), "option_value" => "normal"));
if ($auth_zid === "") {
	print_row(array("caption" => "Captcha", "text_key" => "captcha"));
}
end_tab();

beg_tab("Labels");
$list = db_get_list("label", "label_name", array("reportable" => 1));
$keys = array_keys($list);
for ($i = 0; $i < count($list); $i++) {
	$item = $list[$keys[$i]];
	print_row(array("caption" => '<span class="label" style="background-color: ' . $item["background_color"] . '; color: ' . $item["foreground_color"] . ';">' . $item["label_name"] . '</span>', "check_key" => "label_" . $item["label_id"]));
}
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
	writeln('		["Link","Unlink"]');
	writeln('	]');
	writeln('});');
	writeln();
	writeln('</script>');
}

box_right("Report");

print_footer(["form" => true]);
