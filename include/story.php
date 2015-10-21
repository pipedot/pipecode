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

function print_story_box($story_id, $topic_id, $keywords, $title, $clean_body, $dirty_body, $zid)
{
	global $auth_user;
	global $doc_root;
	global $protocol;
	global $server_name;

	$story = db_get_rec("story", $story_id);
	$topic = db_get_rec("topic", $topic_id);

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

	beg_form();
	writeln('<h1>Preview</h1>');
	$a["body"] = $story["body"];
	$a["title"] = $title;
	$a["link"] = item_link(TYPE_STORY, $story_id, $story);
	$a["info"] = content_info($story, $topic);
	$a["image"] = content_image($topic);
	$a["comments"] = count_comments($story_id, TYPE_STORY);
	print_content($a);

	writeln('<h1>Edit</h1>');
	beg_tab();
	print_row(array("caption" => "Title", "text_key" => "title", "text_value" => $title));
	print_row(array("caption" => "Topic", "option_key" => "topic_id", "option_value" => $topic_id, "option_list" => $topic_list, "option_keys" => $topic_keys));
	print_row(array("caption" => "Keywords", "text_key" => "keywords", "text_value" => $story["keywords"]));
	print_row(array("caption" => "Story", "textarea_key" => "story", "textarea_value" => $dirty_body, "textarea_height" => "400"));
	end_tab();

	box_two('<a href="/similar">Keyword Search</a>', 'Publish,Preview');

	end_form();
	end_main();
	print_footer();
}


function print_story($story)
{
	global $auth_user;
	global $auth_zid;
	global $doc_root;
	global $protocol;
	global $server_name;
	global $twitter_enabled;

	if (!is_array($story)) {
		$story = db_get_rec("story", $story);
	}
	$topic = db_get_rec("topic", $story["topic_id"]);
	$pipe = db_get_rec("pipe", $story["pipe_id"]);
	$story_id = $story["story_id"];
	$story_code = crypt_crockford_encode($story_id);

	$a["body"] = $story["body"];
	$a["title"] = $story["title"];
	$a["link"] = item_link(TYPE_STORY, $story_id, $story);
	$a["info"] = content_info($story, $topic);
	$a["image"] = content_image($topic, $story);
	$a["comments"] = count_comments($story_id, TYPE_STORY);

	$a["actions"] = [];
	$count = similar_count($story);
	if ($count > 0) {
		$a["actions"][] = "<a href=\"$protocol://$server_name/story/$story_code/similar\" class=\"icon-16 news-16\">Similar</a>";
	}
	if ($auth_user["editor"]) {
		if ($twitter_enabled && $story["tweet_id"] == 0) {
			if (is_file("$doc_root/www/images/tweet-16.png")) {
				$a["actions"][] = "<a href=\"$protocol://$server_name/story/$story_code/tweet\" class=\"icon-16 tweet-16\">Tweet</a>";
			} else {
				$a["actions"][] = "<a href=\"$protocol://$server_name/story/$story_code/tweet\" class=\"icon-16 music-16\">Tweet</a>";
			}
		}
		$a["actions"][] = "<a href=\"$protocol://$server_name/story/$story_code/image\" class=\"icon-16 picture-16\">Image</a>";
		$a["actions"][] = "<a href=\"$protocol://$server_name/story/$story_code/edit\" class=\"icon-16 notepad-16\">Edit</a>";
	}

	print_content($a);
}


function print_journal($journal_id)
{
	global $auth_zid;
	global $zid;

	$journal = db_get_rec("journal", $journal_id);
	$journal_code = crypt_crockford_encode($journal_id);

	$a["body"] = make_photo_links($journal["body"]);
	$a["title"] = $journal["title"];
	$a["link"] = item_link(TYPE_JOURNAL, $journal_id, $journal);
	$a["info"] = content_info($journal, $journal["topic"]);
	$a["comments"] = count_comments($journal_id, TYPE_JOURNAL);

	if ($zid == $auth_zid) {
		$a["actions"][] = "<a href=\"/journal/$journal_code/edit\" class=\"icon-16 notepad-16\">Edit</a>";
		$a["actions"][] = "<a href=\"/journal/$journal_code/media\" class=\"icon-16 clip-16\">Media</a>";
		if ($journal["publish_time"] == 0) {
			$a["actions"][] = "<a href=\"/journal/$journal_code/publish\" class=\"icon-16 certificate-16\">Publish</a>";
		}
	} else {
		$a["actions"] = [];
	}

	print_content($a);
}


function print_news_large($article)
{
	global $protocol;
	global $server_name;

	$article_id = $article["article_id"];
        $feed = db_find_rec("feed", $article["feed_id"]);

	$a["body"] = make_photo_links($article["body"]);
	$a["title"] = $article["title"];
	$a["link"] = item_link(TYPE_ARTICLE, $article_id, $article);
	$a["info"] = content_info($article, $feed);
	//$a["comments"] = count_comments($article_id, TYPE_ARTICLE);
	$a["view"] = "<a class=\"icon-16 globe-16\" href=\"" . $article["link"] . "\">View Site</a>";

	if ($article["thumb_id"] > 0 && !string_has($a["body"], "<img ")) {
		$image = "$protocol://$server_name/thumb/" . crypt_crockford_encode($article["thumb_id"]) . ".jpg";
		$a["image"] = "<a href=\"" . $a["link"] . "\"><img class=\"story-image-128\" src=\"$image\" alt=\"story image\"></a>";
	}

	$a["actions"][] = stream_vote_box($article_id);

	print_content($a);
}


function print_news($a)
{
	global $protocol;
	global $server_name;

	$article_id = $a["article_id"];
	$short_code = crypt_crockford_encode($article_id);
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

	$comments = count_comments($article_id, TYPE_ARTICLE);

	if ($thumb_id > 0) {
		writeln('<article class="news-image">');
		writeln('<table class="news-table">');
		writeln('	<tr>');
		writeln('		<td class="news-picture"><a href="' . $protocol . '://' . $server_name . '/article/' . $short_code . '"><img src="' . $protocol . '://' . $server_name . '/thumb/' . $thumb_code . '.jpg"></a></td>');
		writeln('		<td>');
		writeln('			<div class="article-preview">');
		writeln('				<div class="article-link"><a href="' . $protocol . '://' . $server_name . '/article/' . $short_code . '">' . $a["title"] . '</a></div>');
		writeln('				<div class="article-info">' . $info . '</div>');
		writeln('				<div class="article-description">' . $a["description"] . '</div>');
		writeln('			</div>');
		writeln('			<div class="article-footer">');
		writeln('				<div class="article-footer-left"><a href=""><a href="' . $protocol . '://' . $server_name . '/article/' . $short_code . '">' . $comments["tag"] . '</a></a></div>');
		writeln('				<div class="article-footer-right"><div style="display: inline-block">' . stream_vote_box($article_id) . '</div></div>');
		writeln('			</div>');
		writeln('		</td>');
		writeln('	</tr>');
		writeln('</table>');
		writeln('</article>');
	} else {
		writeln('<article class="news-text">');
		writeln('			<div class="article-preview">');
		writeln('		<div class="article-link"><a href="' . $protocol . '://' . $server_name . '/article/' . $short_code . '">' . $a["title"] . '</a></div>');
		writeln('		<div class="article-info">' . $info . '</div>');
		writeln('		<div class="article-description">' . $a["description"] . '</div>');
		writeln('	</div>');
		writeln('	<div class="article-footer">');
		writeln('		<div class="article-footer-left"><a href=""><a href="' . $protocol . '://' . $server_name . '/article/' . $short_code . '">' . $comments["tag"] . '</a></a></div>');
		writeln('		<div class="article-footer-right"><div style="display: inline-block">' . stream_vote_box($article_id) . '</div></div>');
		writeln('	</div>');
		writeln('</article>');
	}
}


function content_info($article, $area = false)
{
	global $protocol;
	global $server_name;

	if (array_key_exists("author_name", $article)) {
		$by = $article["author_name"];
		if ($by != "") {
			if ($article["author_link"] != "") {
				$by = '<a href="' . $article["author_link"] . '" rel="author">' . $by . '</a>';
			}
		}
	} else {
		if (array_key_exists("author_zid", $article)) {
			$zid = $article["author_zid"];
		} else if (array_key_exists("zid", $article)) {
			$zid = $article["zid"];
		} else {
			$zid = "";
		}
		$by = user_link($zid, ["tag" => true, "author" => true]);
	}
	$info = "by <address>$by</address>";
	if (array_key_exists("feed_title", $article)) {
		$info .= " from <a href=\"$protocol://$server_name/feed/" . $article["feed_slug"] . "\"><b>" . $article["feed_title"] . "</b></a>";
	}
	if (is_array($area)) {
		if (array_key_exists("feed_id", $area)) {
			$info .= " from <a href=\"$protocol://$server_name/feed/" . $area["slug"] . "\"><b>" . $area["title"] . "</b></a>";
		} else {
			$info .= " in <a href=\"$protocol://$server_name/topic/" . $area["slug"] . "\"><b>" . $area["topic"] . "</b></a>";
		}
	} else if ($area) {
		$info .= " in <a href=\"" . user_link($zid) . "topic/" . clean_url($area) . "\"><b>" . $article["topic"] . "</b></a>";
	}

	if (array_key_exists("time", $article)) {
		$time = $article["time"];
	} else if (array_key_exists("publish_time", $article)) {
		$time = $article["publish_time"];
	} else {
		$time = -1;
	}
	if ($time == 0) {
		$info .= " as draft";
	} else if ($time > 0) {
		$date = "<time datetime=\"" .  date("c", $time) . "\">" . date("Y-m-d H:i", $time) . "</time>";
		if (array_key_exists("story_id", $article) && array_key_exists("pipe_id", $article)) {
			$date = "<a href=\"/pipe/" . crypt_crockford_encode($article["pipe_id"]) . "\">$date</a>";
		}
		$info .= " on $date";
	}

	$article_id = item_id($article);
	if ($article_id > 0) {
		$article_code = crypt_crockford_encode($article_id);
		$info .= " (<a href=\"$protocol://$server_name/$article_code\">#$article_code</a>)";
	}

	return trim($info);
}


function content_image($topic, $article = [])
{
	global $auth_user;
	global $protocol;
	global $server_name;

	if (array_key_exists("image_id", $article)) {
		$image_id = $article["image_id"];
	} else {
		$image_id = 0;
	}
	if ($auth_user["story_image_style"] == 3 && $image_id > 0) {
		if ($image_id > 0) {
			$image = db_get_rec("image", $image_id);
			$image_url = $image["parent_url"];
			$image_path = public_path($image["time"]) . "/i$image_id.256x256.jpg";
		}
		return "<a href=\"$image_url\"><img alt=\"story image\" class=\"story-image-128\" src=\"$image_path\"></a>";
	} else if ($auth_user["story_image_style"] == 1) {
		return "";
	} else {
		return "<a href=\"$protocol://$server_name/topic/" . $topic["slug"] . "\" class=\"story-icon-64 " . $topic["icon"] . "-64\"></a>";
	}
}


function print_content($a)
{
	if (array_key_exists("class", $a)) {
		$class = $a["class"];
	} else {
		$class = "story";
	}
	if (array_key_exists("link", $a)) {
		$title = "<a href=\"" . $a["link"] . "\">" . $a["title"] . "</a>";
	} else {
		$title = $a["title"];
	}
	if (array_key_exists("image", $a)) {
		$image = $a["image"];
	} else {
		$image = "";
	}
	if (array_key_exists("comments", $a)) {
		$view = "<a href=\"" . $a["link"] . "\">" . $a["comments"]["tag"] . "</a>";
	} else if (array_key_exists("view", $a)) {
		$view = $a["view"];
	} else {
		$view = "";
	}
	if (array_key_exists("actions", $a)) {
		$actions = "<ul>";
		for ($i = 0; $i < count($a["actions"]); $i++) {
			$actions .= "<li>" . $a["actions"][$i] . "</li>";
		}
		$actions .= "</ul>";
	} else {
		$actions = "";
	}

	writeln("<article class=\"$class\">");
	writeln("	<header>");
	writeln("		<h1>$title</h1>");
	writeln("		<div>" . $a["info"] . "</div>");
	writeln("	</header>");
	writeln("	<div>$image" . $a["body"] . "</div>");
	writeln("	<footer>");
	writeln("		<div>$view</div>");
	writeln("		$actions");
	writeln("	</footer>");
	writeln("</article>");
}
