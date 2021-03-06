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

include("story.php");
include("clean.php");

if (string_has($s2, "-") && $s3 === "") {
	$date = $s2;
	$date_beg = strtotime("$date GMT");
	if ($date_beg === false) {
		fatal("Invalid date");
	}
	$date_end = $date_beg + DAYS;
	$story_date = gmdate("Y-m-d", $date_beg);

	$spinner[] = ["name" => "Story", "link" => "/story/"];
	$spinner[] = ["name" => $story_date, "link" => "/story/$story_date/"];

	print_header();

	$row = sql("select story_id from story where publish_time > ? and publish_time < ? order by publish_time desc", $date_beg, $date_end);
	if (count($row) == 0) {
		writeln('<p>' . get_text('No stories published on $1.', gmdate("Y-m-d", $date_beg)) . '</p>');
	}
	for ($i = 0; $i < count($row); $i++) {
		print_story($row[$i]["story_id"]);
	}

	print_footer();
} else {
	$story = item_request(TYPE_STORY);
	$story_date = gmdate("Y-m-d", $story["publish_time"]);;
	$story_code = crypt_crockford_encode($story["story_id"]);

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
	$meta .= "<meta property=\"og:url\" content=\"http://$server_name/story/$story_code\">\n";
	$meta .= "<meta property=\"og:description\" content=\"" . make_description($story["body"]) . "\">\n";
	$meta .= "<meta property=\"og:image\" content=\"http://$server_name$image_path\">\n";
	if ($https_enabled) {
		$meta .= "<meta property=\"og:image:secure_url\" content=\"https://$server_name$image_path\">\n";
	}
	//$meta .= "<meta property=\"og:image:type\" content=\"$image_type\">\n";
	//$meta .= "<meta property=\"og:image:width\" content=\"256\">\n";
	//$meta .= "<meta property=\"og:image:height\" content=\"256\">\n";
	$meta .= "<meta property=\"og:site_name\" content=\"$server_title\">\n";
	$meta .= "<link rel=\"image_src\" href=\"http://$server_name$image_path\" type=\"$image_type\">\n";

	$spinner[] = ["name" => "Story", "link" => "/story/"];
	if ($s2 == $story_date) {
		$spinner[] = ["name" => $story_date, "link" => "/story/$story_date/"];
		$spinner[] = ["name" => $story["title"], "short" => $story_code, "link" => "/story/$story_date/" . $story["slug"]];
	} else {
		$spinner[] = ["name" => $story["title"], "short" => $story_code, "link" => "/story/$story_code"];
	}

	print_header();

//	if (cache_beg("story.$story_code.html")) {
		print_story($story);
		print_comments(TYPE_STORY, $story);
//		cache_end();
//	}

	print_footer();
}
