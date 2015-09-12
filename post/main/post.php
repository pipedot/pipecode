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
include("post.php");
include("mail.php");

$item = item_request();
if ($item["short_type_id"] == TYPE_COMMENT) {
	$root_id = $item["root_id"];
	$parent_id = $item["comment_id"];
	$type_id = $item["short_type_id"];
} else {
	$root_id = $item[$item["short_type"] . "_id"];
	$parent_id = 0;
	$type_id = $item["short_type_id"];
}

$root_item = db_get_rec("short", $root_id);
$root_type_id = $root_item["type_id"];

$subject = clean_subject();
list($clean_body, $dirty_body) = clean_body();
$answer = http_post_string("answer", array("required" => false));
if ($auth_zid === "") {
	$zid = "";
	$coward = true;
	if (http_post("post")) {
		if (!captcha_verify($answer)) {
			fatal("Captcha failed");
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

if (http_post("preview")) {
	$zid = $auth_zid;

	print_header("Post Comment");
	print_main_nav("stories");
	beg_main("cell");

	writeln('<h1>Preview</h1>');
	writeln('<p>Check your links before you post!</p>');
	writeln('<div class="box">');
	print render_comment($subject, ($coward ? "" : $zid), $time, "", $clean_body, 0, 0);
	writeln('</div>');
	writeln('</article>');
	writeln('</div>');

	print_post_box($root_id, $subject, $dirty_body, $coward);

	end_main();
	print_footer();
	die();
}

if ($auth_zid === "" || $zid === "") {
	if (db_has_rec("ban_ip", $remote_ip)) {
		fatal("Your IP address is banned for sending junk messages");
	}
}

$comment = db_new_rec("comment");
$comment["comment_id"] = create_short(TYPE_COMMENT);
$comment["body"] = $clean_body;
$comment["edit_time"] = $time;
$comment["parent_id"] = $parent_id;
$comment["publish_time"] = $time;
$comment["remote_ip"] = $remote_ip;
$comment["root_id"] = $root_id;
$comment["subject"] = $subject;
$comment["zid"] = $zid;
db_set_rec("comment", $comment);

send_notifications($comment);

revert_view_time($root_type_id, $root_id);
header("Location: " . item_link($root_type_id, $root_id));
