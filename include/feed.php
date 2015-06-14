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

include("$doc_root/lib/simplepie/simplepie.php");


function download_feed($uri)
{
	//$feed = db_get_rec("feed", $fid);

	//print "feed [$fid] uri [" . $feed["uri"] . "] ";

	//$data = @file_get_contents($feed["uri"]);
	$data = @file_get_contents($uri);
	//print "len [" . strlen($data) . "] ";

	return $data;
}


function save_feed($feed_id, $data)
{
	global $redirect_url;

	$feed = db_get_rec("feed", $feed_id);

	//print "feed [$fid] uri [" . $feed["uri"] . "] ";

	//$data = @file_get_contents($feed["uri"]);
	//print "len [" . strlen($data) . "] ";

	$sp = new SimplePie();
	$sp->set_raw_data($data);
	//$sp->set_feed_url($feed["uri"]);
	$sp->init();

	$title = clean_html($sp->get_title(), "text");
	$link = get_feed_link($sp, $feed["uri"]);
	$link = string_clean($link, "[a-z][A-Z][0-9]~#%&()-_+=[];:./?");
	$copyright = clean_html($sp->get_copyright(), "text");
	$description = clean_html($sp->get_description(), "text");
	//$link = $sp->get_permalink();
	//if ($link == "") {
	//	$a = explode("/", $feed["uri"]);
	//	if (count($a) >= 3) {
	//		$link = $a[0] . "//" . $a[2] . "/";
	//	}
	//}

	$feed["copyright"] = $copyright;
	$feed["description"] = $description;
	$feed["link"] = $link;
	$feed["time"] = time();
	$feed["title"] = $title;
	db_set_rec("feed", $feed);

	//print "title [$title] link [$link] ";

	//writeln("link [" . $link . "]<br>");
	//writeln("title [" . $title . "]<br>");
	//sql("delete from article where feed_id = ?", $feed_id);
	$count = 0;

	foreach ($sp->get_items(0, 8) as $item) {
		$item_link = $item->get_permalink();
		if (empty($item_link)) {
			$item_link = $link;
		} else {
			$link = string_clean($link, "[a-z][A-Z][0-9]~#%&()-_+=[];:./?");
		}
		//$item_title = html_entity_decode($item->get_title());
		$item_guid = clean_html($item->get_id(), "text");
		if (strlen($item_guid) > 200) {
			$item_guid = crypt_sha256($item_guid);
		}
		$author = $item->get_author();
		if (empty($author)) {
			$author_name = "";
			$author_link = "";
		} else {
			$author_name = clean_html($author->get_name(), "text");
			$author_link = string_clean($author->get_link(), "[a-z][A-Z][0-9]~#%&()-_+=[];:./?");
			if ($author_name == "") {
				$author_name = clean_html($author->get_email(), "text");
			}
		}
		$item_title = clean_html($item->get_title(), "text");

		// XXX: workaround for "double encoded" entities
		$item_title = html_entity_decode($item_title);
		$item_title = html_entity_decode($item_title);
		$item_title = htmlentities($item_title, ENT_NOQUOTES);

		$item_html = $item->get_content() . "";
		$item_description = $item->get_description() . "";
		if ($item_html === $item_description) {
			$item_body = clean_html($item_html, "article");
			$item_description = make_description($item_body);
		} else {
			$item_body = clean_html($item_html, "article");
			$item_description = clean_html($item_description, "text");
		}
		$item_time = $item->get_date("U");
		if (empty($item_time)) {
			$item_time = time();
		}

		if (!empty($item_title)) {
			//writeln("link [$item_link] title [$item_title] description [$item_description]<br>");
			//writeln("link [$item_link] title [$item_title] time [$item_time]<br>");

			if (db_has_rec("article", array("guid" => $item_guid))) {
				$article = db_get_rec("article", array("guid" => $item_guid));
			} else {
				$article = db_new_rec("article");
				$article["article_id"] = create_short(TYPE_ARTICLE);
			}
			$article_html = http_cache($item_link);

			if ($article["thumb_id"] == 0) {
				$meta = get_meta($article_html);
				//if (array_key_exists("image_secure", $opengraph)) {
				//	$image_url = $opengraph["image_secure"];
				//} else if (array_key_exists("image", $opengraph)) {
				//	$image_url = $opengraph["image"];
				//} else {
				//	$image_url = "";
				//}
				//if ($image_url != "") {
				if (array_key_exists("image", $meta)) {
					//$tmp_image_id = download_image($item_link, $meta["image"]);
					//if ($tmp_image_id > 0) {
					//	$article["image_id"] = promote_image($tmp_image_id);
					//} else {
					//	$article["image_id"] = -1;
					//}
					$article["thumb_id"] = create_thumb($meta["image"]);
				}
			}

			if (string_uses($redirect_url, "[a-z][A-Z][0-9]~#%&()-_+=[];:./?")) {
				$article["redirect_url"] = $redirect_url;
			}

			$article["author_name"] = $author_name;
			$article["author_link"] = $author_link;
			$article["body"] = $item_body;
			$article["description"] = $item_description;
			$article["feed_html"] = $item_html;
			$article["feed_id"] = $feed_id;
			$article["guid"] = $item_guid;
			$article["title"] = $item_title;
			$article["link"] = $item_link;
			$article["publish_time"] = $item_time;
			db_set_rec("article", $article);
			$count++;
		}
	}

	//print "items [$count]\n";
}


function get_feed_link($sp, $uri)
{
	$link = clean_html($sp->get_permalink(), "text");
	if ($link == "") {
		$a = explode("/", $uri);
		if (count($a) >= 3) {
			$link = $a[0] . "//" . $a[2] . "/";
		}
	}

	return $link;
}


function add_feed($uri)
{
	if (db_has_rec("feed", array("uri" => $uri))) {
		//die("feed already exists [$uri]");
		$feed = db_get_rec("feed", array("uri" => $uri));
		return $feed["feed_id"];
	}

	$data = download_feed($uri);
	$sp = new SimplePie();
	$sp->set_raw_data($data);
	$sp->init();
	$title = $sp->get_title();
	$copyright = clean_html($sp->get_copyright(), "text");
	$description = clean_html($sp->get_description(), "text");
	$link = get_feed_link($sp, $uri);
	$count = $sp->get_item_quantity();

	if (strlen($title) == 0 || $count == 0) {
		die("unable to parse feed [$uri]");
		//die("unable to parse feed [$uri] data [$data]");
	}

	$feed_id = create_short(TYPE_FEED);
	$feed_code = crypt_crockford_encode($feed_id);

	$feed = db_new_rec("feed");
	$feed["feed_id"] = $feed_id;
	$feed["copyright"] = $copyright;
	$feed["decription"] = $description;
	$feed["time"] = time();
	$feed["uri"] = $uri;
	$feed["title"] = $title;
	$feed["link"] = $link;
	$feed["slug"] = make_feed_slug($feed);
	db_set_rec("feed", $feed);

	save_feed($feed_id, $data);

	$favicon = find_favicon($link);
	save_favicon($short_code, $favicon);
	//die("favicon [$favicon]");

	make_feed_slug($feed);

	return $feed_id;
}


function clean_feed_title($title)
{
	$title = str_replace("&amp;", "&", $title);

	return $title;
}


function update_feed($feed_id)
{
	$feed = db_get_rec("feed", $feed_id);
	$data = download_feed($feed["uri"]);
	save_feed($feed_id, $data);
}


function make_feed_slug($feed)
{
	//$feed = db_get_rec("feed", $feed_id);
	$slugs = array();

	// best choice is from the feed title
	$title = clean_url($feed["title"]);
	if ($title != "" && string_uses(substr($title, 0, 1), "[a-z]")) {
		$slugs[] = $title;
	}

	$a = parse_url($feed["link"]);
	$domain = @$a["host"];
	if ($domain != "") {
		$a = explode(".", $domain);
		if (count($a) > 1) {
			// second choice is the site name
			$site = $a[count($a) - 2];
			if (string_uses(substr($site, 0, 1), "[a-z]")) {
				$slugs[] = $site;
			}
		}
		// third choice is the domain name
		if (string_uses(substr($domain, 0, 1), "[a-z]")) {
			$slugs[] = clean_url($domain);
		}
	}

	// fallback to the short code
	$slugs[] = crypt_crockford_encode($feed["feed_id"]);

	for ($i = 0; $i < count($slugs); $i++) {
		$slug = $slugs[$i];
		$row = sql("select slug from feed where slug = ? and feed_id <> ?", $slug, $feed["feed_id"]);
		if (count($row) == 0) {
			//writeln("feed_id [$feed_id] slug [$slug] all [" . implode(" ", $slugs) . "]");
			//$feed["slug"] = $slug;
			//db_set_rec("feed", $feed);
			return $slug;
		}
	}
}


function update_article_thumbnail($article_id)
{
	global $redirect_url;

	$article = db_get_rec("article", $article_id);
	if ($article["redirect_link"] != "") {
		$url = $article["redirect_link"];
	} else {
		$url = $article["link"];
	}
	$html = http_cache($url);
	$meta = get_meta($html);
	if ($redirect_url != "" && $redirect_url != $url) {
		$article["redirect_link"] = $redirect_url;
	}
	if (array_key_exists("image", $meta)) {
		$article["thumb_id"] = create_thumb($meta["image"]);
	}
	db_set_rec("article", $article);
}


function print_feed_page($zid)
{
	beg_main("tri-table");

	for ($c = 0; $c < 3; $c++) {
		if ($c == 0) {
			writeln('<div class="tri-left">');
		} else if ($c == 1) {
			writeln('<div class="tri-center">');
		} else {
			writeln('<div class="tri-right">');
		}

		$row = sql("select feed_id from feed_user where zid = ? and col = ? order by pos", $zid, $c);
		for ($f = 0; $f < count($row); $f++) {
			$feed = db_get_rec("feed", $row[$f]["feed_id"]);

			writeln('	<div class="feed-title"><a href="' . $feed["link"] . '">' . $feed["title"] . '</a></div>');
			writeln('	<ul class="feed-body">');
			//$items = db_get_list("article", "publish_time desc", array("feed_id" => $feed["feed_id"]));
			$row2 = sql("select link, title from article where feed_id = ? order by publish_time desc limit 0, 8", $feed["feed_id"]);
			//$item_keys = array_keys($items);
			//for ($j = 0; $j < count($items); $j++) {
			for ($j = 0; $j < count($row2); $j++) {
				//$item = $items[$item_keys[$j]];
				//writeln('		<li><a href="' . $item["link"] . '">' . clean_feed_title($item["title"]) . '</a></li>');
				writeln('		<li><a href="' . $row2[$j]["link"] . '">' . clean_feed_title($row2[$j]["title"]) . '</a></li>');
			}
			writeln('	</ul>');
		}
		writeln('</div>');
	}

	end_main();
}


function get_meta($html)
{
	$doc = new DOMDocument();
	@$doc->loadHTML($html);
	$a = array();
	$image_secure = "";

	$metas = $doc->getElementsByTagName("link");
	for ($i = 0; $i < $metas->length; $i++) {
		$meta = $metas->item($i);
		$rel = strtolower(@$meta->getAttribute("rel"));
		if ($rel == "image_src") {
			$href = @$meta->getAttribute("content");
			$href = clean_html($href, "text");
			$a["image"] = $href;
		}
	}

	$metas = $doc->getElementsByTagName("meta");
	for ($i = 0; $i < $metas->length; $i++) {
		$meta = $metas->item($i);
		$name = strtolower(@$meta->getAttribute("name"));
		if ($name == "description") {
			$content = @$meta->getAttribute("content");
			$content = clean_html($content, "text");
			$a["description"] = $content;
		}
	}

	for ($i = 0; $i < $metas->length; $i++) {
		$meta = $metas->item($i);
		$property = strtolower(@$meta->getAttribute("property"));
		$content = @$meta->getAttribute("content");
		$content = clean_html($content, "text");
		if ($property == "og:title") {
			//print "title [$content]\n";
			$a["title"] = $content;
		} else if ($property == "og:description") {
			//print "description [$content]\n";
			$a["description"] = $content;
		} else if ($property == "og:image") {
			//print "image [$content]\n";
			$content = @$meta->getAttribute("content");
			$content = string_clean($content, "[a-z][A-Z][0-9]~#%&()-_+=[];:./?");
			$a["image"] = $content;
		} else if ($property == "og:image:secure_url") {
			//$a["image_secure"] = $content;
			$content = @$meta->getAttribute("content");
			$content = string_clean($content, "[a-z][A-Z][0-9]~#%&()-_+=[];:./?");
			$image_secure = $content;
		} else if ($property == "og:site_name") {
			//print "site_name [$content]\n";
			$a["site_name"] = $content;
		}
	}

	if ($image_secure != "") {
		$a["image"] = $image_secure;
	}

	for ($i = 0; $i < $metas->length; $i++) {
		$meta = $metas->item($i);
		$name = strtolower(@$meta->getAttribute("name"));
		if ($name == "twitter:image:src") {
			$content = @$meta->getAttribute("content");
			$content = string_clean($content, "[a-z][A-Z][0-9]~#%&()-_+=[];:./?");
			$a["image"] = $content;
		}
	}

	return $a;
}
