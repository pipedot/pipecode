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

include("clean.php");

require_login();

$comment = item_request(TYPE_COMMENT);
$clean_body = $comment["body"];
$dirty_body = dirty_html($clean_body);
if ($auth_user["javascript_enabled"] && $auth_user["wysiwyg_enabled"]) {
	$dirty_body = str_replace("\n", "<br>", $dirty_body);
}

require_mine($comment["zid"]);

$spinner[] = ["name" => "Comment", "link" => "/comment/"];
$spinner[] = ["name" => $comment["subject"], "short" => $comment["short_code"], "link" => "/comment/" . $comment["short_code"]];
$spinner[] = ["name" => "Edit", "link" => "/comment/edit"];

print_header(["title" => "Edit Comment", "form" => true]);

//writeln('<h1>' . get_text('Edit Comment') . '</h1>');

beg_tab();
print_row(array("caption" => "Subject", "text_key" => "subject", "text_value" => $comment["subject"]));
end_tab();

writeln('<div class="box">');
writeln('<textarea name="body" style="width: 100%; height: 100px">' . $dirty_body . '</textarea>');
writeln('</div>');

box_right("Save");

if ($auth_user["javascript_enabled"] && $auth_user["wysiwyg_enabled"]) {
	writeln('<script type="text/javascript" src="/lib/ckeditor/ckeditor.js"></script>');
	writeln('<script type="text/javascript">');
	writeln();
	writeln('CKEDITOR.timestamp = "' . fs_time("$doc_root/www/lib/ckeditor/config.js") . '";');
	writeln('CKEDITOR.replace("body",');
	writeln('{');
	writeln('	resize_enabled: false,');
	writeln('	enterMode: CKEDITOR.ENTER_BR,');
	writeln('	toolbar :');
	writeln('	[');
	writeln('		["Bold","Italic","Underline","Strike","Subscript","Superscript"],');
	writeln('		["NumberedList","BulletedList","Blockquote"],');
	writeln('		["Link","Unlink"]');
	writeln('	]');
	writeln('});');
	writeln();
	writeln('</script>');
}

print_footer(["form" => true]);
