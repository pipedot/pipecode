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
include("render.php");
include("captcha.php");
include("post.php");

$type = http_get_string("type", array("required" => false, "valid" => "[a-z]"));
$comment_id = http_get_string("comment_id", array("required" => false, "valid" => "[a-z][0-9]_"));
$root_id = http_get_string("root_id", array("required" => false, "valid" => "[a-z][0-9]_"));

print_header("Post Comment");

print_left_bar("main", "stories");
beg_main("cell");

if ($comment_id != "") {
	$comment = db_get_rec("comment", $comment_id);
	$type = $comment["type"];
	$subject = $comment["subject"];
	$zid = $comment["zid"];

	$re = false;
	if (strlen($subject) >= 4) {
		if (substr($subject, 0, 4) == "Re: ") {
			$re = true;
		}
	}
	if (!$re) {
		$subject = "Re: " . $comment["subject"];
	}

	writeln('<div style="margin-bottom: 8px">');
	print render_comment($comment["subject"], $zid, $comment["time"], $comment["comment_id"], $comment["body"], 0, $comment["short_id"]);
	writeln('</div>');
	writeln('</article>');
	writeln('</div>');

} else {
	$subject = "";
}
check_article_type($type);

print_post_box($type, $root_id, $subject, "", false);

end_main();
print_footer();
