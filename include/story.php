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

function print_story_box($story_id, $tid, $icon, $title, $clean_body, $dirty_body, $zid)
{
	global $doc_root;

	$story = db_get_rec("story", $story_id);
	$topic = db_get_rec("topic", $tid);
	$topic = $topic["topic"];

	print_header();

	print_left_bar("main", "stories");
	beg_main("cell");

	$topic_list = array();
	$topic_keys = array();
	$topics = db_get_list("topic", "topic");
	$k = array_keys($topics);
	for ($i = 0; $i < count($topics); $i++) {
		$topic_list[] = $topics[$k[$i]]["topic"];
		$topic_keys[] = $k[$i];
	}

	$icon_list = array();
	$a = fs_dir("$doc_root/www/images");
	for ($i = 0; $i < count($a); $i++) {
		if (substr($a[$i], -7) == "-64.png") {
			$icon_list[] = substr($a[$i], 0, -7);
		}
	}

	beg_form();
	writeln('<h1>Preview</h1>');
	$a = array();
	$a["title"] = $title;
	$a["time"] = $story["publish_time"];
	$a["pipe_id"] = $story["pipe_id"];
	$a["zid"] = $zid;
	$a["topic"] = $topic;
	$a["icon"] = $icon;
	$a["body"] = $clean_body;
	$a["comments"] = count_comments("story", $story_id);
	print_article($a);

	writeln('<h1>Edit</h1>');
	beg_tab();
	print_row(array("caption" => "Title", "text_key" => "title", "text_value" => $title));
	print_row(array("caption" => "Topic", "option_key" => "tid", "option_value" => $tid, "option_list" => $topic_list, "option_keys" => $topic_keys));
	print_row(array("caption" => "Icon", "option_key" => "icon", "option_value" => $icon, "option_list" => $icon_list));
	print_row(array("caption" => "Story", "textarea_key" => "story", "textarea_value" => $dirty_body, "textarea_height" => "400"));
	end_tab();

	writeln('<table class="fill" style="margin-bottom: 8px">');
	writeln('	<tr>');
	writeln('		<td><a href="/icons">Icons</a></td>');
	writeln('		<td style="text-align: right"><input name="publish" type="submit" value="Publish"/> <input name="preview" type="submit" value="Preview"/></td>');
	writeln('	</tr>');
	writeln('</table>');
	//right_box("Publish,Preview");

	end_form();
	end_main();
	print_footer();
}


function print_story($story)
{
	global $server_name;
	global $auth_user;
	global $auth_zid;

	if (!is_array($story)) {
		$story = db_get_rec("story", $story);
	}
	$topic = db_get_rec("topic", $story["tid"]);
	$pipe = db_get_rec("pipe", $story["pipe_id"]);

	$a["body"] = $story["body"];
	$a["icon"] = $story["icon"];
	$a["image_id"] = $story["image_id"];
	$a["pipe_id"] = $story["pipe_id"];
	$a["pipe_short_id"] = $pipe["short_id"];
	$a["short_id"] = $story["short_id"];
	$a["story_id"] = $story["story_id"];
	$a["time"] = $story["publish_time"];
	$a["title"] = $story["title"];
	$a["topic"] = $topic["topic"];
	$a["tweet_id"] = $story["tweet_id"];
	$a["zid"] = $story["author_zid"];
	$a["comments"] = count_comments("story", $story["story_id"]);

	print_article($a);
}


function print_journal($journal_id)
{
	global $auth_zid;

	$journal = db_get_rec("journal", $journal_id);

	$body = $journal["body"];
	$body = make_photo_links($body);

	$a["body"] = $body;
	$a["photo_id"] = $journal["photo_id"];
	$a["short_id"] = $journal["short_id"];
	$a["journal_id"] = $journal_id;
	$a["time"] = $journal["publish_time"];
	$a["title"] = $journal["title"];
	$a["topic"] = $journal["topic"];
	$a["zid"] = $journal["zid"];
	$a["comments"] = count_comments("journal", $journal_id);

	print_article($a);
}


function print_article($a)
{
	global $server_name;
	global $auth_user;
	global $auth_zid;
	global $protocol;
	global $doc_root;
	global $accounting_enabled;

	if (array_key_exists("time", $a)) {
		$time = $a["time"];
	} else {
		$time = time();
	}
	$zid = $a["zid"];
	$by = user_page_link($zid, true, true, true);
	//$by = "<b>" . $by . "</b>";
	if (array_key_exists("story_id", $a)) {
		$story_id = $a["story_id"];
	} else {
		$story_id = "";
	}
	if (array_key_exists("pipe_id", $a)) {
		$pipe_id = $a["pipe_id"];
	} else {
		$pipe_id = "";
	}
	if (array_key_exists("pipe_short_id", $a)) {
		$pipe_short_id = $a["pipe_short_id"];
	} else {
		$pipe_short_id = 0;
	}
	if (array_key_exists("journal_id", $a)) {
		$journal_id = $a["journal_id"];
	} else {
		$journal_id = "";
	}
	if (array_key_exists("bug_id", $a)) {
		$bug_id = $a["bug_id"];
		$labels = $a["labels"];
	} else {
		$bug_id = "";
	}
	if (array_key_exists("pipe_id", $a)) {
		$pipe_id = $a["pipe_id"];
	} else {
		$pipe_id = 0;
	}
	if (array_key_exists("score", $a)) {
		$score = $a["score"];
	} else {
		$score = 0;
	}
	if (array_key_exists("image_id", $a)) {
		$image_id = $a["image_id"];
	} else {
		$image_id = 0;
	}
	if (array_key_exists("tweet_id", $a)) {
		$tweet_id = $a["tweet_id"];
	} else {
		$tweet_id = 0;
	}
	if (array_key_exists("short_id", $a)) {
		$short_code = crypt_crockford_encode($a["short_id"]);
		$short = " (<a href=\"$protocol://$server_name/$short_code\">#$short_code</a>)";
	} else {
		$short_code = "";
		$short = "";
	}
	$image_style = $auth_user["story_image_style"];
	if (array_key_exists("topic", $a)) {
		$topic = $a["topic"];
		$topic_slug = clean_url($a["topic"]);
	} else {
		$topic = "";
		$topic_slug = "";
	}
	$story = make_clickable($a["body"]);
	if (array_key_exists("icon", $a)) {
		$icon = $a["icon"];
	} else {
		$icon = "";
	}
	$title = $a["title"];
	$slug = clean_url($title);
	if ($time == 0) {
		$date_label = "as";
		$date_value = "draft";
		$day = "";
	} else {
		$date_label = "on";
		// schema.org is ugly
		//$date_value = "<span itemprop=\"datePublished\" content=\"" .  date("c", $time) . "\">" . date("Y-m-d H:i", $time) . "</span>";
		$date_value = "<time datetime=\"" .  date("c", $time) . "\">" . date("Y-m-d H:i", $time) . "</time>";
		$day = gmdate("Y-m-d", $time);
	}
	if ($pipe_short_id > 0) {
		$date = "$date_label <a href=\"/pipe/" . crypt_crockford_encode($pipe_short_id) . "\">$date_value</a>";
	} else {
		$date = "$date_label $date_value";
	}

	if ($image_style == 1) {
		// no image
		$image_path = "";
	} else if ($image_style == 2) {
		// icon
		if ($icon == "") {
			$image_path = "";
			$image_url = "";
		} else {
			$image_path = "/images/$icon-64.png";
			$image_url = "";
			$width = "64";
		}
	} else {
		if ($image_id == 0) {
			$image_path = "";
		} else {
			$image = db_get_rec("image", $image_id);
			// XXX: if (high res mode) {
			if (true) {
				$size = "256x256";
			} else {
				$size = "128x128";
			}
			$image_url = $image["parent_url"];
			$image_path = public_path($image["time"]) . "/i$image_id.$size.jpg";
		}
	}

	// schema.org is ugly
	//writeln("<article class=\"story\" itemscope itemtype=\"http://schema.org/Article\">");

	writeln("<article class=\"story\">");
	if ($story_id != "") {
		$title_link = "/story/$day/$slug";
	} else if ($pipe_id != "") {
		$title_link = "/pipe/$short_code";
	} else if ($journal_id != "" && $time > 0) {
		$title_link = "/journal/$day/$slug";
	} else {
		$title_link = "";
	}
	if ($title_link == "") {
		// schema.org is ugly
		//writeln("	<h1><span itemprop=\"name\">$title</span></h1>");

		writeln("	<h1>$title</h1>");
	} else {
		// schema.org is ugly
		//writeln("	<h1><a href=\"$title_link\"><span itemprop=\"name\">$title</span></a></h1>");

		writeln("	<h1><a href=\"$title_link\">$title</a></h1>");
	}

	if ($journal_id == "") {
		$topic_link = "$protocol://$server_name/topic/$topic_slug";
	} else {
		$topic_link = user_page_link($zid) . "topic/$topic_slug";
	}

	if ($topic == "") {
		writeln("	<h2>by <address>$by</address> $date$short</h2>");
	} else {
		// schema.org is ugly
		//writeln("	<h2>by <address>$by</address> in <a href=\"$topic_link\"><b itemprop=\"articleSection\">$topic</b></a> $date$short</h2>");

		writeln("	<h2>by <address>$by</address> in <a href=\"$topic_link\"><b>$topic</b></a> $date$short</h2>");
	}

	if ($image_path != "") {
		if ($image_url != "") {
			// schema.org is ugly
			//writeln("	<div><a href=\"$image_url\"><img alt=\"story image\" class=\"story_image_128\" itemprop=\"image\" src=\"$image_path\"/></a>$story</div>");

			writeln("	<div><a href=\"$image_url\"><img alt=\"story image\" class=\"story_image_128\" src=\"$image_path\"/></a>$story</div>");
		} else {
			writeln("	<div><img alt=\"story icon\" style=\"float: right; margin-left: 8px; margin-bottom: 8px;" . "px\" src=\"$image_path\"/>$story</div>");
		}
	} else {
		writeln("	<div>$story</div>");
	}
	writeln("	<footer>");
	if ($story_id != "") {
		writeln("		<div><a href=\"/story/$day/$slug\">{$a["comments"]["tag"]}</a></div>");
		writeln("		<div class=\"right\">");
		if ($accounting_enabled) {
			writeln("			<a href=\"/story/$short_code/tip\" class=\"icon_16 coins_16\">Tip</a> | ");
		}
		if (@$auth_user["editor"]) {
			if ($tweet_id == 0) {
				if (is_file("$doc_root/www/images/tweet-16.png")) {
					writeln("			<a href=\"/story/$short_code/tweet\" class=\"icon_16 tweet_16\">Tweet</a> | ");
				} else {
					writeln("			<a href=\"/story/$short_code/tweet\" class=\"icon_16 music_16\">Tweet</a> | ");
				}
			}
			writeln("			<a href=\"/story/$short_code/image\" class=\"icon_16 picture_16\">Image</a> | ");
			writeln("			<a href=\"/story/$short_code/edit\" class=\"icon_16 notepad_16\">Edit</a>");
		}
		writeln("		</div>");
	} else if ($pipe_id != "") {
		writeln("		<div>{$a["comments"]["tag"]}</div>");
		writeln("		<div class=\"right\">score <b>$score</b></div>");
	} else if ($journal_id != "") {
		if ($time > 0) {
			writeln("		<div><a href=\"/journal/$day/$slug\">{$a["comments"]["tag"]}</a></div>");
		} else {
			writeln("		<div><a href=\"/journal/$short_code\">{$a["comments"]["tag"]}</a></div>");
		}
		if ($zid == $auth_zid) {
			writeln("		<div class=\"right\">");
			//writeln("			<a href=\"/journal/$short_code/image\" class=\"icon_16 picture_16\">Image</a> | ");
			writeln("			<a href=\"/journal/$short_code/edit\" class=\"icon_16 notepad_16\">Edit</a> | ");
			writeln("			<a href=\"/journal/$short_code/media\" class=\"icon_16 clip_16\">Media</a>" . ($time == 0 ? ' | ' : ''));
			if ($time == 0) {
				writeln("			<a href=\"/journal/$short_code/publish\" class=\"icon_16 certificate_16\">Publish</a>");
			}
			writeln("		</div>");
		}
	} else if ($bug_id != "") {
		writeln("		<div>{$a["comments"]["tag"]}</div>");
		writeln("		<div class=\"right\">");
		if ($auth_user["editor"] || $auth_user["admin"]) {
			writeln("			<a href=\"/bug/$short_code/edit\" class=\"icon_16 notepad_16\">Edit</a> | ");
			if (!$a["closed"]) {
				writeln("			<a href=\"/bug/$short_code/close\" class=\"icon_16 close_16\">Close</a>");
			}
		}
		if ($auth_zid !== "" && $a["closed"]) {
			writeln("			<a href=\"/bug/$short_code/open\" class=\"icon_16 undo_16\">Open</a>");
		}
		writeln("		</div>");
	}
	writeln("	</footer>");
	writeln("</article>");
}
