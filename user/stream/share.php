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

include("clean.php");
include("stream.php");
include("image.php");

if ($auth_zid == "") {
	die("sign in to share");
}

if (http_post()) {
	list($clean_body, $dirty_body) = clean_body(false);
	$tags = clean_tags();
	$url = clean_link();
	$time = time();

	if (isset($_FILES["upload"]) && $_FILES["upload"]["tmp_name"] != "") {
		//die("[" . $_FILES["upload"]["tmp_name"] . "]");
		$data = fs_slurp($_FILES["upload"]["tmp_name"]);
		$hash = crypt_sha256($data);
		$src_img = @imagecreatefromstring($data);
		if ($src_img === false) {
			die("unable to open uploaded file");
		}
		$image_id = create_image($src_img, $_FILES["upload"]["name"], $hash);
		//die("image width [" . imagesx($src_img) . "]");
		$link_id = 0;
	} else {
		if ($url == "") {
			$link_id = 0;
		} else {
			$link = array();
			$link["link_id"] = 0;
			$link["image_id"] = 0;
			$link["subject"] = slurp_title($url);
			$link["time"] = $time;
			$link["url"] = $url;
			db_set_rec("link", $link);
			$link = db_get_rec("link", array("time" => $time, "url" => $url));
			$link_id = $link["link_id"];
		}
		$image_id = 0;
	}

	if ($clean_body == "" && $url == "" && $image_id == 0) {
		die("nothing to share");
	}

	$article = array();
	$article["article_id"] = 0;
	$article["archive"] = $time + 86400 * 90;
	$article["body"] = $clean_body;
	$article["full_body"] = "";
	$article["subject"] = "";
	$article["time"] = $time;
	$article["zid"] = $auth_zid;
	db_set_rec("article", $article);
	$article = db_get_rec("article", array("zid" => $auth_zid, "time" => $time));
	$article_id = $article["article_id"];

	$card = array();
	$card["card_id"] = 0;
	$card["article_id"] = $article_id;
	$card["image_id"] = $image_id;
	$card["link_id"] = $link_id;
	db_set_rec("card", $card);
	$card = db_get_rec("card", array("article_id" => $article_id));
	$card_id = $card["card_id"];

	for ($i = 0; $i < count($tags); $i++) {
		if (!db_has_rec("tag", array("tag" => $tags[$i]))) {
			$tag = array();
			$tag["tag_id"] = 0;
			$tag["tag"] = $tags[$i];
			db_set_rec("tag", $tag);
		}
		$tag = db_get_rec("tag", array("tag" => $tags[$i]));
		$tag_id = $tag["tag_id"];

		$card_tags = array();
		$card_tags["card_id"] = $card_id;
		$card_tags["tag_id"] = $tag_id;
		db_set_rec("card_tags", $card_tags);
	}

	//die("article_id [$article_id] card_id [$card_id]");
	if ($link_id == 0) {
		header("Location: " . user_page_link($auth_zid) . "stream/");
	} else {
		header("Location: $protocol://$server_name/card/$card_id/image");
	}
	die();
}

print_header("Share");
beg_main();
//writeln('<form method="post" enctype="multipart/form-data">');
beg_form("", "file");
//writeln('<input type="hidden" name="MAX_FILE_SIZE" value="5000000"/>');

writeln('<h1>Share</h1>');

//$option_keys = array("text", "image", "link");
//$option_list = array("Text Only", "Image", "Link");

//beg_tab();
//print_row(array("caption" => "Type", "option_key" => "type", "option_keys" => $option_keys, "option_list" => $option_list));
//end_tab();

//writeln('<h2>Text</h2>');
writeln('<div style="margin-bottom: 8px">');
writeln('<textarea name="body" style="width: 100%; height: 100px"></textarea>');
writeln('</div>');

if ($auth_user["javascript_enabled"] && $auth_user["wysiwyg_enabled"]) {
	writeln('<script type="text/javascript" src="/lib/ckeditor/ckeditor.js"></script>');
	writeln('<script type="text/javascript">');
	writeln();
	writeln('CKEDITOR.replace("body",');
	writeln('{');
	writeln('	resize_enabled: false,');
	writeln('	enterMode: CKEDITOR.ENTER_BR,');
	writeln('	toolbar :');
	writeln('	[');
	writeln('		["Bold","Italic","Underline","Strike"],');
	writeln('		["NumberedList","BulletedList","Blockquote"],');
	writeln('		["Link","Unlink"]');
	writeln('	]');
	writeln('});');
	writeln();
	writeln('</script>');
}

beg_tab();
print_row(array("caption" => "Tags", "text_key" => "tags"));
end_tab();

beg_tab();
print_row(array("caption" => "Link", "text_key" => "link"));
end_tab();

beg_tab();
writeln('	<tr>');
writeln('		<td>');
writeln('			<div class="row_tab">');
writeln('				<div class="row_caption">Picture</div>');
writeln('				<div><input name="upload" type="file" style="width: 100%"/></div></div>');
writeln('			</div>');
writeln('		</td>');
writeln('	</tr>');
end_tab();

right_box("Share");

end_form();
end_main();
print_footer();
