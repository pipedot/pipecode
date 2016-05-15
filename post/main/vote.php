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

require_login();

$article_code = http_post_string("code", ["valid" => "[A-Z][0-9]"]);
$value = http_post_int("value", ["required" => false]);
$noscript = http_post_bool("noscript");

$article_id = crypt_crockford_decode($article_code);
$article_type_id = item_type_id($article_id);
$article_type = item_type($article_type_id);
if ($article_type_id == TYPE_ARTICLE || $article_type_id == TYPE_CARD) {
	$article = db_get_rec($article_type, $article_id);
} else {
	die("error: invalid type");
}
if ($value < -1 || $value > 1) {
	die("error: invalid value");
}

$stream_vote = db_find_rec("stream_vote", ["zid" => $auth_zid, "article_id" => $article_id]);
if ($stream_vote) {
	db_del_rec("stream_vote", ["zid" => $auth_zid, "article_id" => $article_id]);
	$value = 0;
} else if ($value != 0) {
	$stream_vote = db_new_rec("stream_vote");
	$stream_vote["zid"] = $auth_zid;
	$stream_vote["article_id"] = $article_id;
	$stream_vote["time"] = $now;
	$stream_vote["value"] = $value;
	db_set_rec("stream_vote", $stream_vote);
}

if ($article_type_id == TYPE_ARTICLE) {
	$stream_main = db_find_rec("stream_main", $article_id);
	if (!$stream_main) {
		$stream_main = db_new_rec("stream_main");
		$stream_main["article_id"] = $article_id;
		$stream_main["time"] = $article["publish_time"];
		db_set_rec("stream_main", $stream_main);
	}
}

if ($value <= 0) {
	db_del_rec("stream_user", ["zid" => $auth_zid, "article_id" => $article_id]);
} else {
	$stream_user = db_find_rec("stream_user", ["zid" => $auth_zid, "article_id" => $article_id]);
	if ($stream_user) {
		$stream_user["time"] = $now;
	} else {
		$stream_user = db_new_rec("stream_user");
		$stream_user["zid"] = $auth_zid;
		$stream_user["article_id"] = $article_id;
	}
	db_set_rec("stream_user", $stream_user);
}

$score = get_stream_score($article_id);

if ($noscript) {
	item_redirect($article_type_id, $article_id, $article);
} else {
	writeln('{');
	writeln('	"code": "' . $article_code . '",');
	writeln('	"score": ' . $score . ',');
	writeln('	"value": ' . $value);
	writeln('}');
}
