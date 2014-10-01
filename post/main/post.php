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
include("mail.php");

$type = http_get_string("type", array("required" => false, "valid" => "[a-z]"));
$comment_id = http_get_string("comment_id", array("required" => false, "valid" => "[a-z][0-9]_"));
$root_id = http_get_string("root_id", array("required" => false, "valid" => "[a-z][0-9]_"));

$subject = clean_subject();
list($clean_body, $dirty_body) = clean_body();
$answer = http_post_string("answer", array("required" => false));
if ($auth_zid == "") {
	$zid = "";
	$coward = true;
	if (http_post("post")) {
		if (!captcha_verify($answer)) {
			die("captcha failed");
		}
	}
} else {
	$coward = http_post_bool("coward");
	if ($coward) {
		$zid = "";
	} else {
		$zid = $auth_zid;
	}
}
$time = time();

if ($comment_id != "") {
	$comment = db_get_rec("comment", $comment_id);
	$type = $comment["type"];
	$root_id = $comment["root_id"];
} else if ($type == "" || $root_id == "") {
	die("unknown root");
}
check_article_type($type);

if (http_post("preview")) {
	$zid = $auth_zid;

	print_header("Post Comment");

	print_left_bar("main", "stories");
	beg_main("cell");

	writeln('<h1>Preview</h1>');
	writeln('<p>Check your links before you post!</p>');
	writeln('<div style="margin-bottom: 8px">');
	print render_comment($subject, ($coward ? "" : $zid), $time, "", $clean_body, 0, 0);
	writeln('</div>');
	writeln('</article>');
	writeln('</div>');

	print_post_box($type, $root_id, $subject, $dirty_body, $coward);

	end_main();
	print_footer();
	die();
}

$comment = array();
$comment["comment_id"] = create_id($zid);
$comment["body"] = $clean_body;
$comment["edit_time"] = $time;
$comment["parent_id"] = $comment_id;
$comment["publish_time"] = $time;
$comment["rating"] = "";
$comment["root_id"] = $root_id;
$comment["score"] = 0;
$comment["short_id"] = create_short("comment", $comment["comment_id"]);
$comment["subject"] = $subject;
$comment["type"] = $type;
$comment["zid"] = $zid;
db_set_rec("comment", $comment);

$comment = db_get_rec("comment", $comment["comment_id"]);
send_notifications($comment);

revert_view_time($type, $root_id);
header("Location: " . item_link($type, $root_id));
