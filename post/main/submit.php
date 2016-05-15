<?
//
// Pipecode - distributed social network
// Copyright (C) 2014-2016 Bryan Beicker <bryan@pipedot.org>
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
include("story.php");
include("captcha.php");
include("submit.php");

require_feature("submit");

$title = clean_subject();
list($clean_body, $dirty_body) = clean_body();
$topic_id = http_post_int("topic_id");
$answer = http_post_string("answer", array("required" => false));
$time = time();

if ($auth_zid == "" && !captcha_verify($answer)) {
	fatal("Captcha failed");
}

$topic = db_get_rec("topic", $topic_id);

if (http_post("preview")) {
	print_submit_box($title, $dirty_body, $clean_body, $topic_id, true);
	finish();
}

$pipe = db_new_rec("pipe");
$pipe["pipe_id"] = create_short(TYPE_PIPE);
$pipe["author_zid"] = $auth_zid;
$pipe["body"] = $clean_body;
$pipe["closed"] = 0;
$pipe["edit_zid"] = "";
$pipe["icon"] = $topic["icon"];
$pipe["reason"] = "";
$pipe["slug"] = clean_url($title);
$pipe["topic_id"] = $topic_id;
$pipe["title"] = $title;
$pipe["time"] = $time;
db_set_rec("pipe", $pipe);

header("Location: /pipe/" . crypt_crockford_encode($pipe["pipe_id"]));
