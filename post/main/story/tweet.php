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

require_once("$doc_root/lib/twitteroauth/twitteroauth.php");

require_feature("twitter");
require_editor();

$story = item_request(TYPE_STORY);
$topic = db_get_rec("topic", $story["topic_id"]);
$topic = $topic["topic"];
if ($story["tweet_id"] > 0) {
	fatal("Already tweeted");
}

$status_text = http_post_string("status_text", array("len" => 140, "valid" => "[a-z][A-Z][0-9]`~!@#$%^&*()_+-={}|[]\\:\";',./? "));

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
$parameters = array('status' => $status_text);
$status = $connection->post('statuses/update', $parameters);
$tweet_id = $status->id;

if ($tweet_id > 0) {
	$story["tweet_id"] = $tweet_id;
	db_set_rec("story", $story);
	header("Location: /story/" . $story["short_code"]);
} else {
	var_dump($status);
}
