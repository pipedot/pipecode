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

function print_story_box($story_id, $tid, $icon, $title, $clean_body, $dirty_body, $zid)
{
	global $doc_root;

	$story = db_get_rec("story", $story_id);
	$topic = db_get_rec("topic", $tid);
	$topic = $topic["topic"];

	print_header();

	print_main_nav("stories");
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
	$a["type_id"] = TYPE_STORY;
	$a["title"] = $title;
	$a["time"] = $story["publish_time"];
	$a["pipe_id"] = $story["pipe_id"];
	$a["zid"] = $zid;
	$a["topic"] = $topic;
	$a["icon"] = $icon;
	$a["body"] = $clean_body;
	$a["comments"] = count_comments(TYPE_STORY, $story_id);
	print_article($a);

	writeln('<h1>Edit</h1>');
	beg_tab();
	print_row(array("caption" => "Title", "text_key" => "title", "text_value" => $title));
	print_row(array("caption" => "Topic", "option_key" => "tid", "option_value" => $tid, "option_list" => $topic_list, "option_keys" => $topic_keys));
	print_row(array("caption" => "Icon", "option_key" => "icon", "option_value" => $icon, "option_list" => $icon_list));
	print_row(array("caption" => "Keywords", "text_key" => "keywords", "text_value" => $story["keywords"]));
	print_row(array("caption" => "Story", "textarea_key" => "story", "textarea_value" => $dirty_body, "textarea_height" => "400"));
	end_tab();

	box_two('<a href="/icons">Icons</a>', 'Publish,Preview');

	end_form();
	end_main();
	print_footer();
}


function print_story($story)
{
	global $server_name;
	global $auth_user;
	global $auth_zid;
	global $auth_user;
	global $translate_enabled;

	if (!is_array($story)) {
		$story = db_get_rec("story", $story);
	}
	$topic = db_get_rec("topic", $story["tid"]);
	$pipe = db_get_rec("pipe", $story["pipe_id"]);

	$src_lang = $story["lang"];
	$dst_lang = $auth_user["lang"];
	if ($translate_enabled && $src_lang != $dst_lang) {
		$title = translate($story["title"], $dst_lang, $src_lang);
		$body = translate($story["body"], $dst_lang, $src_lang);
	} else {
		$title = $story["title"];
		$body = $story["body"];
	}

	$a["type_id"] = TYPE_STORY;
	$a["body"] = $body;
	$a["icon"] = $story["icon"];
	$a["keywords"] = $story["keywords"];
	$a["image_id"] = $story["image_id"];
	$a["src_lang"] = $src_lang;
	$a["dst_lang"] = $dst_lang;
	$a["pipe_id"] = $story["pipe_id"];
	$a["slug"] = $story["slug"];
	$a["story_id"] = $story["story_id"];
	$a["time"] = $story["publish_time"];
	$a["title"] = $title;
	$a["topic"] = $topic["topic"];
	$a["tweet_id"] = $story["tweet_id"];
	$a["zid"] = $story["author_zid"];
	$a["comments"] = count_comments(TYPE_STORY, $story["story_id"]);

	print_article($a);
}


function print_journal($journal_id)
{
	global $auth_zid;

	$journal = db_get_rec("journal", $journal_id);

	$body = $journal["body"];
	$body = make_photo_links($body);

	$a["type_id"] = TYPE_JOURNAL;
	$a["body"] = $body;
	$a["photo_id"] = $journal["photo_id"];
	$a["journal_id"] = $journal_id;
	$a["time"] = $journal["publish_time"];
	$a["title"] = $journal["title"];
	$a["topic"] = $journal["topic"];
	$a["zid"] = $journal["zid"];
	$a["comments"] = count_comments(TYPE_JOURNAL, $journal_id);

	print_article($a);
}


function print_news($a)
{
	global $protocol;
	global $server_name;

	$short_code = crypt_crockford_encode($a["article_id"]);
	if (array_key_exists("thumb_id", $a)) {
		$thumb_id = $a["thumb_id"];
	} else {
		$thumb_id = 0;
	}
	if ($thumb_id > 0) {
		$thumb_code = crypt_crockford_encode($thumb_id);
	} else {
		$thumb_code = "";
	}

	$info = "";
	$by = $a["author_name"];
	if ($by != "") {
		$by = $a["author_name"];
		if ($a["author_link"] != "") {
			$by = '<a href="' . $a["author_link"] . '" rel="author">' . $by . '</a>';
		}
		$info = "by <address>$by</address>";
	}
	if (array_key_exists("feed_title", $a)) {
		$info .= " from <a href=\"$protocol://$server_name/feed/" . $a["feed_slug"] . "\"><b>" . $a["feed_title"] . "</b></a>";
	}
	if ($a["publish_time"] > 0) {
		$info .= " on <time datetime=\"" .  date("c", $a["publish_time"]) . "\">" . date("Y-m-d H:i", $a["publish_time"]) . "</time>";
	}
	$info .= " (<a href=\"$protocol://$server_name/$short_code\">#$short_code</a>)";
	$info = trim($info);

	if ($thumb_id > 0) {
		writeln('<article class="news-image">');
		writeln('<table>');
		writeln('	<tr>');
		writeln('		<td><a href="' . $protocol . '://' . $server_name . '/article/' . $short_code . '"><img src="' . $protocol . '://' . $server_name . '/thumb/' . $thumb_code . '.jpg"></a></td>');
		writeln('		<td>');
		writeln('			<div class="article-preview">');
		writeln('				<div class="article-link"><a href="' . $protocol . '://' . $server_name . '/article/' . $short_code . '">' . $a["title"] . '</a></div>');
		writeln('				<div class="article-info">' . $info . '</div>');
		writeln('				<div class="article-description">' . $a["description"] . '</div>');
		writeln('			</div>');
		writeln('		</td>');
		writeln('	</tr>');
		writeln('</table>');
		writeln('</article>');
	} else {
		writeln('<article class="news-text">');
		writeln('<table>');
		writeln('	<tr>');
		writeln('		<td>');
		writeln('			<div class="article-preview">');
		writeln('				<div class="article-link"><a href="' . $protocol . '://' . $server_name . '/article/' . $short_code . '">' . $a["title"] . '</a></div>');
		writeln('				<div class="article-info">' . $info . '</div>');
		writeln('				<div class="article-description">' . $a["description"] . '</div>');
		writeln('			</div>');
		writeln('		</td>');
		writeln('	</tr>');
		writeln('</table>');
		writeln('</article>');
	}
}


function print_article($a)
{
	global $server_name;
	global $auth_user;
	global $auth_zid;
	global $protocol;
	global $doc_root;
	global $translate_enabled;

	if (array_key_exists("time", $a)) {
		$time = $a["time"];
	} else {
		$time = time();
	}
	if (array_key_exists("zid", $a)) {
		$zid = $a["zid"];
		$by = user_link($zid, ["tag" => true, "author" => true]);
	} else {
		$zid = "";
		$by = "";
	}
	$article_id = 0;
	$feed_id = 0;
	$feed_title = "";
	$feed_link = "";
	if (array_key_exists("article_id", $a)) {
		$article_id = $a["article_id"];
		if ($a["feed_id"] > 0) {
			$feed_id = $a["feed_id"];
			$feed = db_get_rec("feed", $feed_id);
			$feed_title = $feed["title"];
			//$feed_link = $feed["link"];
			$feed_link = "$protocol://$server_name/feed/" . crypt_crockford_encode($feed_id);
		}
		if ($a["author_name"] != "") {
			$by = $a["author_name"];
		}
		if ($a["author_link"] != "") {
			$by = "<a href=\"{$a["author_link"]}\">$by</a>";
		}
	}
	if (array_key_exists("story_id", $a)) {
		$story_id = $a["story_id"];
	} else {
		$story_id = 0;
	}
	if (array_key_exists("pipe_id", $a)) {
		$pipe_id = $a["pipe_id"];
	} else {
		$pipe_id = 0;
	}
	if (array_key_exists("journal_id", $a)) {
		$journal_id = $a["journal_id"];
	} else {
		$journal_id = 0;
	}
	if (array_key_exists("bug_id", $a)) {
		$bug_id = $a["bug_id"];
		$labels = $a["labels"];
	} else {
		$bug_id = 0;
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
	if (array_key_exists("thumb_id", $a)) {
		$thumb_id = $a["thumb_id"];
	} else {
		$thumb_id = 0;
	}
	if (array_key_exists("tweet_id", $a)) {
		$tweet_id = $a["tweet_id"];
	} else {
		$tweet_id = 0;
	}

	$using = "";
	$lang = "";
	if ($translate_enabled && array_key_exists("src_lang", $a)) {
		$src_lang = $a["src_lang"];
		$dst_lang = $a["dst_lang"];
		if ($src_lang != $dst_lang) {
			$using = " using <span title=\"" . lang_name($src_lang) . " to " . lang_name($dst_lang) . "\"><b>Google Translate</b></a>";
			$lang = " lang=\"$dst_lang-x-mtfrom-$src_lang\"";
		}
	} else {
		$src_lang = "en";
		$dst_lang = "en";
	}

	$type = item_type($a["type_id"]);
	$short_name = $type . "_id";
	if (array_key_exists($short_name, $a)) {
		$short_id = $a[$short_name];
		$short_code = crypt_crockford_encode($short_id);
		$short = " (<a href=\"$protocol://$server_name/$short_code\">#$short_code</a>)";
	} else {
		$short_id = 0;
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
	if ($story_id > 0 || $journal_id > 0) {
		$story = make_clickable($a["body"]);
	} else {
		$story = $a["body"];
	}
	if (array_key_exists("icon", $a)) {
		$icon = $a["icon"];
	} else {
		$icon = "";
	}
	$title = $a["title"];
	if (array_key_exists("slug", $a)) {
		$slug = $a["slug"];
	} else {
		$slug = clean_url($title);
	}
	if ($time == 0) {
		$date_label = "as";
		$date_value = "draft";
		$day = "";
	} else {
		$date_label = "on";
		$date_value = "<time datetime=\"" .  date("c", $time) . "\">" . date("Y-m-d H:i", $time) . "</time>";
		$day = gmdate("Y-m-d", $time);
	}
	if ($pipe_id > 0) {
		$date = "$date_label <a href=\"/pipe/" . crypt_crockford_encode($pipe_id) . "\">$date_value</a>";
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
		if ($image_id <= 0) {
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
	if ($thumb_id > 0) {
		$image_url = $a["link"];
		$image_path = "/thumb/" . crypt_crockford_encode($thumb_id) . ".jpg";
	}
	if ($article_id > 0 && string_has($story, "<img ")) {
		$image_url = "";
		$image_path = "";
	}

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
	writeln("	<header>");
	if ($title_link == "") {
		writeln("		<h1>$title</h1>");
	} else {
		writeln("		<h1><a href=\"$title_link\">$title</a></h1>");
	}

	if ($journal_id == "") {
		$topic_link = "$protocol://$server_name/topic/$topic_slug";
	} else {
		$topic_link = user_link($zid) . "topic/$topic_slug";
	}

	if ($feed_title != "") {
		if ($by == "") {
			writeln("		<div>from <a href=\"$feed_link\"><b>$feed_title</b></a> $date$short</div>");
		} else {
			writeln("		<div>by <address>$by</address> from <a href=\"$feed_link\"><b>$feed_title</b></a> $date$short</div>");
		}
	} else if ($topic == "") {
		writeln("		<div>by <address>$by</address> $date$short</div>");
	} else {
		writeln("		<div>by <address>$by</address> in <a href=\"$topic_link\"><b>$topic</b></a>$using $date$short</div>");
	}
	writeln("	</header>");

	if ($image_path != "") {
		if ($image_url != "") {
			writeln("	<div$lang><a href=\"$image_url\"><img alt=\"story image\" class=\"story-image-128\" src=\"$image_path\"></a>$story</div>");
		} else {
			writeln("	<div$lang><img alt=\"story icon\" style=\"float: right; margin-left: 8px; margin-bottom: 8px;" . "px\" src=\"$image_path\">$story</div>");
		}
	} else {
		writeln("	<div$lang>$story</div>");
	}

	$link = "";
	$actions = [];
	if ($story_id != "") {
		$link = "<a href=\"/story/$day/$slug\">{$a["comments"]["tag"]}</a>";
		if ($a["keywords"] != "") {
			$count = similar_count($a);
			if ($count > 0) {
				$actions[] = "<a href=\"/story/$short_code/similar\" class=\"icon-16 news-16\">Similar</a>";
			}
		}
		if (@$auth_user["editor"]) {
			if ($tweet_id == 0) {
				if (is_file("$doc_root/www/images/tweet-16.png")) {
					$actions[] = "<a href=\"/story/$short_code/tweet\" class=\"icon-16 tweet-16\">Tweet</a>";
				} else {
					$actions[] = "<a href=\"/story/$short_code/tweet\" class=\"icon-16 music-16\">Tweet</a>";
				}
			}
			$actions[] = "<a href=\"/story/$short_code/image\" class=\"icon-16 picture-16\">Image</a>";
			$actions[] = "<a href=\"/story/$short_code/edit\" class=\"icon-16 notepad-16\">Edit</a>";
		}
	} else if ($pipe_id != "") {
		$link = $a["comments"]["tag"];
		$actions[] = "score <b>$score</b>";
	} else if ($journal_id != "") {
		if ($time > 0) {
			$link = "<a href=\"/journal/$day/$slug\">{$a["comments"]["tag"]}</a>";
		} else {
			$link = "<a href=\"/journal/$short_code\">{$a["comments"]["tag"]}</a>";
		}
		if ($zid == $auth_zid) {
			$actions[] = "<a href=\"/journal/$short_code/edit\" class=\"icon-16 notepad-16\">Edit</a>";
			$actions[] = "<a href=\"/journal/$short_code/media\" class=\"icon-16 clip-16\">Media</a>";
			if ($time == 0) {
				$actions[] = "<a href=\"/journal/$short_code/publish\" class=\"icon-16 certificate-16\">Publish</a>";
			}
		}
	} else if ($bug_id != "") {
		$link = $a["comments"]["tag"];
		if ($auth_user["editor"] || $auth_user["admin"]) {
			$actions[] = "<a href=\"/bug/$short_code/edit\" class=\"icon-16 notepad-16\">Edit</a>";
			if (!$a["closed"]) {
				$actions[] = "<a href=\"/bug/$short_code/close\" class=\"icon-16 close-16\">Close</a>";
			}
		}
		if ($auth_zid !== "" && $a["closed"]) {
			$actions[] = "<a href=\"/bug/$short_code/open\" class=\"icon-16 undo-16\">Open</a>";
		}
	} else if ($article_id > 0) {
		$link = "<a class=\"icon-16 globe-16\" href=\"{$a["link"]}\">View Site</a>";
	} else {
		$link = "<div><b>0</b> comments</div>";
	}

	if (count($actions) == 0) {
		writeln("	<footer>$link</footer>");
	} else {
		writeln("	<footer>");
		writeln("		<div>$link</div>");
		writeln("		<div>" . implode(" | ", $actions) . "</div>");
		writeln("	</footer>");
	}
	writeln("</article>");
}
