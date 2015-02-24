<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
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

if (!$auth_user["admin"]) {
	die("you are not an admin");
}

$feed = find_rec("feed");
$short_code = crypt_crockford_encode($feed["feed_id"]);

if (http_post("delete")) {
	print_header("Edit Feed");
	beg_main();
	beg_form();
	writeln('<h1>Delete Feed</h1>');
	writeln("<p>Are you sure you want to delete the [<b>" . $feed["title"] . "</b>] feed and all of its articles?</p>");
	left_box("Sure");
	end_form();
	end_main();
	print_footer();
	die();
}
if (http_post("sure")) {
	sql("delete from article where feed_id = ?", $feed["feed_id"]);
	sql("delete from feed_user where feed_id = ?", $feed["feed_id"]);
	sql("delete from feed where feed_id = ?", $feed["feed_id"]);
	sql("delete from short where short_id = ?", $feed["feed_id"]);

	//die("deleted feed [$short_code]");
	header("Location: /feed/browse");
	die();
}

$slug = http_post_string("slug", array("valid" => "[a-z][0-9]-", "len" => 200));

if (!string_uses(substr($slug, 0, 1), "[a-z]")) {
	die("slug must start with a letter");
}

$feed["slug"] = $slug;
db_set_rec("feed", $feed);

header("Location: /feed/$slug");

