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
include("captcha.php");
include("image.php");
include("post.php");
include("story.php");

$item = item_request();

print_header("Post Comment");
beg_main();

if ($item["short_type_id"] == TYPE_COMMENT) {
	$subject = $item["subject"];
	$article_id = $item["article_id"];

	$re = false;
	if (strlen($subject) >= 4) {
		if (substr($subject, 0, 4) == "Re: ") {
			$re = true;
		}
	}
	if (!$re) {
		$subject = "Re: " . $item["subject"];
	}
} else {
	$subject = "";
	$article_id = $item[$item["short_type"] . "_id"];
}

$short = db_get_rec("short", $article_id);
$article_type_id = $short["type_id"];
if ($article_type_id == TYPE_STORY) {
	print_story($article_id);
} else if ($article_type_id == TYPE_JOURNAL) {
	print_journal($article_id);
}

if ($item["short_type_id"] == TYPE_COMMENT) {
	writeln('<div class="box">');
	print render_comment($item["subject"], $item["zid"], $item["edit_time"], $item["comment_id"], $item["body"], 0);
	writeln('</div>');
	writeln('</article>');
	writeln('</div>');
}

print_post_box($article_id, $subject, "", false);

end_main();
print_footer();
