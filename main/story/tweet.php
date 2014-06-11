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

require_once("$top_root/lib/twitteroauth/twitteroauth.php");

if (!@$auth_user["editor"]) {
	die("you are not an editor");
}

$sid = (int) $s2;
$story = db_get_rec("story", $sid);
$topic = db_get_rec("topic", $story["tid"]);
$topic = $topic["topic"];
if ($story["tweet_id"] > 0) {
	die("already tweeted");
}
//function getConnectionWithAccessToken($oauth_token, $oauth_token_secret) {
//	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
//	return $connection;
//}


if (http_post()) {
	$status_text = http_post_string("status_text", array("len" => 140, "valid" => "[a-z][A-Z][0-9]`~!@#$%^&*()_+-={}|[]\\:\";',./? "));

	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
	//$connection = getConnectionWithAccessToken($oauth_token, $oauth_token_secret);
	//$content = $connection->get("statuses/home_timeline");
	//var_dump($content);

	$parameters = array('status' => $status_text);
	$status = $connection->post('statuses/update', $parameters);

	//header("Content-type: text/plain");
	//var_dump($status);
	$tweet_id = $status->id;
	//print "tweet_id [$tweet_id]";

	if ($tweet_id > 0) {
		$story["tweet_id"] = $tweet_id;
		db_set_rec("story", $story);
		header("Location: /story/$sid");
		//die("done");
		die();
	} else {
		var_dump($status);
		die("failed");
	}
}

print_header();
print_left_bar("main", "stories");
beg_main("cell");
beg_form();

writeln('<h1>Send Tweet</h1>');

$status_text = $story["title"] . " https://$server_name/story/$sid #$topic";
beg_tab();
print_row(array("caption" => "Status Text", "text_key" => "status_text", "text_value" => $status_text));
end_tab();

right_box("Send");

end_form();
end_main();
print_footer();
