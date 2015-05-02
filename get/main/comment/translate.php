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

include("render.php");

$comment = item_request(TYPE_COMMENT);
$src_hash = crypt_sha256($comment["body"]);

print_header("Translate");
beg_main();

writeln('<h1>Original Language</h1>');
writeln('<h2>' . lang_name($comment["lang"]) . '</h2>');

writeln('<div class="box">');
print render_comment($comment["subject"], $comment["zid"], $comment["publish_time"], $comment["comment_id"], $comment["body"], 0, "", "", $comment["junk_status"], $comment["lang"]);
writeln('</div>');
writeln('</article>');
writeln('</div>');

writeln('<h1>Machine Translated</h1>');

$row = sql("select dst_lang from lang_translation where src_hash = ?", $src_hash);
for ($i = 0; $i < count($row); $i++) {
	writeln('<h2>' . lang_name($row[$i]["dst_lang"]) . '</h2>');
	print render_comment($comment["subject"], $comment["zid"], $comment["publish_time"], $comment["comment_id"], $comment["body"], 0, "", "", $comment["junk_status"], $row[$i]["dst_lang"]);
	writeln('</div>');
	writeln('</article>');
}

box_center('<a href="https://translate.google.com/"><img alt="Powered by Google Translate" src="/images/google-translate.png"></a>');

end_main();
print_footer();
