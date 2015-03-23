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
include("render.php");
include("mail.php");

if ($auth_zid === "") {
	die("error: sign in to reply inline");
}

$item = item_request("comment");
$root_id = $item["root_id"];
$parent_id = $item["comment_id"];
$type = $item["type"];

$subject = clean_subject();
list($clean_body, $dirty_body) = clean_body();
$answer = http_post_string("answer", array("required" => false));
$coward = http_post_bool("coward");
if ($coward) {
	$zid = "";
} else {
	$zid = $auth_zid;
}
$time = time();

$comment = db_new_rec("comment");
$comment["comment_id"] = create_short("comment");
$comment["body"] = $clean_body;
$comment["edit_time"] = $time;
$comment["parent_id"] = $parent_id;
$comment["publish_time"] = $time;
$comment["remote_ip"] = $remote_ip;
$comment["root_id"] = $root_id;
$comment["subject"] = $subject;
$comment["type"] = $type;
$comment["zid"] = $zid;
db_set_rec("comment", $comment);

send_notifications($comment);

//$s = "{\n";
//$s .= "	\"reply\": [\n";
$s = render_comment_json($subject, $zid, $time, $comment["comment_id"], $clean_body, 0);
//$s .= "			]\n";
//$s .= "		}\n";
$s .= "	]\n";
$s .= "}\n";
$s = str_replace("\$level", "", $s);

print $s;
