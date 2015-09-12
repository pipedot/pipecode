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

include("story.php");
include("clean.php");

if (string_has($s2, "-") && $s3 === "") {
	$date = $s2;
	$time_beg = strtotime("$date GMT");
	if ($time_beg === false) {
		fatal("Invalid date");
	}
	$time_end = $time_beg + DAYS;

	print_header();
	print_main_nav("stories");
	beg_main("cell");

	$row = sql("select story_id from story where publish_time > ? and publish_time < ? order by publish_time desc", $time_beg, $time_end);
	if (count($row) == 0) {
		writeln("No stories published on [" . gmdate("Y-m-d", $time_beg) . "]");
	}
	for ($i = 0; $i < count($row); $i++) {
		print_story($row[$i]["story_id"]);
	}

	end_main();
	print_footer();
} else {
	$story = item_request(TYPE_STORY);
	$short_code = crypt_crockford_encode($story["story_id"]);

	if ($story["image_id"] > 0) {
		$image = db_get_rec("image", $story["image_id"]);
		$image_path = public_path($image["time"]) . "/i{$story["image_id"]}.256x256.jpg";
		$image_type = "image/jpeg";
	} else {
		$image_path = "/images/logo-256.png";
		$image_type = "image/png";
	}

	$meta .= "<meta property=\"og:title\" content=\"{$story["title"]}\">\n";
	$meta .= "<meta property=\"og:type\" content=\"article\">\n";
	$meta .= "<meta property=\"og:url\" content=\"http://$server_name/story/$short_code\">\n";
	$meta .= "<meta property=\"og:description\" content=\"" . make_description($story["body"]) . "\">\n";
	$meta .= "<meta property=\"og:image\" content=\"http://$server_name$image_path\">\n";
	$meta .= "<meta property=\"og:image:secure_url\" content=\"https://$server_name$image_path\">\n";
	//$meta .= "<meta property=\"og:image:type\" content=\"$image_type\">\n";
	//$meta .= "<meta property=\"og:image:width\" content=\"256\">\n";
	//$meta .= "<meta property=\"og:image:height\" content=\"256\">\n";
	$meta .= "<meta property=\"og:site_name\" content=\"$server_title\">\n";
	$meta .= "<link rel=\"image_src\" href=\"http://$server_name$image_path\" type=\"$image_type\">\n";

	print_header($story["title"]);
	print_main_nav("stories");
	beg_main("cell");

//	if (cache_beg("story.$short_code.html")) {
		print_story($story);
		print_comments(TYPE_STORY, $story);
//		cache_end();
//	}

	end_main();
	print_footer();
}
