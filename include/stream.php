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

function print_card($card_id)
{
	global $protocol;
	global $server_name;
	global $doc_root;

	$a = array();
	$card = db_get_rec("card", $card_id);
//	$article = db_get_rec("article", $card["article_id"]);
	if ($card["link_url"] != "") {
//		$link = db_get_rec("link", $card["link_id"]);
		$a["link_url"] = $card["link_url"];
		$a["link_subject"] = $card["link_subject"];
		$image_id = $card["image_id"];
		if ($image_id > 0) {
			$image = db_get_rec("image", $image_id);
			$path = public_path($image["time"]) . "/i$image_id.256x256.jpg";
			$a["image_url"] = "$protocol://$server_name$path?" . fs_time("$doc_root/www$path");
		}
	}

	$tags = array();
	$row = sql("select tag from card_tags inner join tag on card_tags.tag_id = tag.tag_id where card_id = ?", $card_id);
	for ($i = 0; $i < count($row); $i++) {
		$tags[] = $row[$i]["tag"];
	}

	$a["card_id"] = $card_id;
	$a["zid"] = $card["zid"];
	$a["time"] = $card["time"];
	$a["votes"] = 0;
	$a["body"] = $card["body"];

	$photo_id = $card["photo_id"];
	if ($photo_id > 0) {
		$a["photo_id"] = $photo_id;
		$photo = db_get_rec("photo", $photo_id);
		$width = 320;
		if ($photo["aspect_width"] == 9 && $photo["aspect_height"] == 16) {
			$width = 320;
			$height = 569;
			if ($photo["has_medium"]) {
				$width = 640;
				$height = 1138;
			}
			$a["photo_class"] = "card_photo_9x16";
		} else if ($photo["aspect_width"] == 3 && $photo["aspect_height"] == 4) {
			$width = 320;
			$height = 427;
			if ($photo["has_medium"]) {
				$width = 640;
				$height = 853;
			}
			$a["photo_class"] = "card_photo_3x4";
		} else if ($photo["aspect_width"] == 1 && $photo["aspect_height"] == 1) {
			$width = 320;
			$height = 320;
			if ($photo["has_medium"]) {
				$width = 640;
				$height = 640;
			}
			$a["photo_class"] = "card_photo_1x1";
		} else if ($photo["aspect_width"] == 4 && $photo["aspect_height"] == 3) {
			$width = 320;
			$height = 240;
			if ($photo["has_medium"]) {
				$width = 640;
				$height = 480;
			}
			$a["photo_class"] = "card_photo_4x3";
		} else if ($photo["aspect_width"] == 16 && $photo["aspect_height"] == 9) {
			$width = 320;
			$height = 180;
			if ($photo["has_medium"]) {
				$width = 640;
				$height = 360;
			}
			$a["photo_class"] = "card_photo_16x9";
		}
		$path = public_path($photo["time"]) . "/p$photo_id.{$width}x{$height}.jpg";
		$a["photo_url"] = "$protocol://$server_name$path?" . fs_time("$doc_root/www$path");
	}

	$a["comments"] = 0;
	$a["tags"] = $tags;

	print_card_small($a);
}


function print_card_small($a)
{
	global $server_name;
	global $protocol;

	$card_id = $a["card_id"];
	$zid = $a["zid"];
	list($user, $host) = explode("@", $zid);
	$user_page_link = user_page_link($zid);
	$profile_picture = profile_picture($zid, 64);
	$time = $a["time"];
	$date = date("Y-m-d H:i", $time);
	$votes = $a["votes"];
	$body = $a["body"];
	if (array_key_exists("link_url", $a)) {
		$link_url = $a["link_url"];
		$link_subject = $a["link_subject"];
		if (array_key_exists("image_url", $a)) {
			$image_url = $a["image_url"];
		} else {
			$image_url = "";
		}
		$u = parse_url($link_url);
		$link_site = $u["host"];
	} else {
		$link_url = "";
	}
	if (array_key_exists("photo_id", $a)) {
		$photo_id = $a["photo_id"];
		$photo_url = $a["photo_url"];
		$photo_class = $a["photo_class"];
	} else {
		$photo_id = 0;
		$photo_url = "";
		$photo_class = "";
	}
	$comments = $a["comments"];
	$tag_links = "";
	if (array_key_exists("tags", $a)) {
		for ($i = 0; $i < count($a["tags"]); $i++) {
			$tag = $a["tags"][$i];
			$tag_links .= "<a class=\"card_tag\" href=\"$protocol://$server_name/tag/$tag\">#$tag</a> ";
		}
		$tag_links = trim($tag_links);
	}

	writeln("<table class=\"card\">");
	writeln("	<tr>");
	writeln("		<td class=\"card_row\">");
	writeln("			<a href=\"$user_page_link\"><img class=\"card_profile\" src=\"$profile_picture\"/></a>");
	writeln("			<div class=\"card_by_box\">");
	writeln("				<a class=\"card_by\" href=\"$user_page_link\">$zid</a>");
	writeln("				<div class=\"card_time\">$date</div>");
	writeln("			</div>");
	writeln("			<div class=\"card_vote\">");
	writeln("				<div class=\"card_vote_box\">");
	writeln("					<img alt=\"Vote Up\" class=\"card_button\" src=\"/images/plus-16.png\" title=\"Vote Up\"/>");
	writeln("					<div class=\"card_vote_count\">$votes</div>");
	writeln("					<img alt=\"Vote Down\" class=\"card_button\" src=\"/images/minus-16.png\" title=\"Vote Down\"/>");
	writeln("				</div>");
	writeln("			</div>");
	writeln("		</td>");
	writeln("	</tr>");
	if ($body != "") {
		writeln("	<tr>");
		writeln("		<td class=\"card_row\">$body</td>");
		writeln("	</tr>");
	}
	if ($photo_url != "") {
		writeln("	<tr>");
		writeln("		<td class=\"card_row\"><a href=\"$protocol://$server_name/photo/$photo_id\"><img alt=\"photo\" class=\"$photo_class\" src=\"$photo_url\"/></a></td>");
		writeln("	</tr>");
	}
	if ($link_url != "") {
		writeln("	<tr>");
		writeln("		<td class=\"card_row_link\">");
		if ($image_url != "") {
			writeln("			<a href=\"$link_url\"><img class=\"card_story_image\" src=\"$image_url\"/></a>");
		}
		writeln("			<div class=\"card_story_box\">");
		writeln("				<a class=\"card_story_link\" href=\"$link_url\">$link_subject</a>");
		writeln("				<a class=\"card_story_site\" href=\"$link_url\">$link_site</a>");
		writeln("			</div>");
		writeln("		</td>");
		writeln("	</tr>");
	}
	writeln("	<tr>");
	writeln("		<td class=\"card_footer\">");
	writeln("			<a class=\"card_comments\" href=\"$protocol://$server_name/card/$card_id\">$comments comments</a>");
	writeln("			<div class=\"card_tags\">$tag_links</div>");
	writeln("			<img alt=\"Options\" class=\"card_button\" src=\"/images/gear-16.png\" title=\"Options\"/>");
	writeln("		</td>");
	writeln("	</tr>");
	writeln('</table>');
}

/*
function print_card_medium($a)
{
	writeln('<table class="card" style="width: 684px; border: 1px solid #9f9f9f; border-radius: 10px; border-collapse: separate; border-spacing: 0px; margin: 4px; background-color: #ffffff">');
	writeln('	<tr>');
	writeln('		<td style="padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 2px;">');
	writeln('			<table style="width: 100%">');
	writeln('				<tr>');
	writeln('					<td style="padding: 2px;"><img style="width: 32px; border-radius: 4px;" src="/pub/profile/bryan.png"/></td>');
	writeln('					<td style="padding: 0px; padding-left: 8px; width: 100%;"><div style="font-weight: bolder; font-size: 10pt; padding-top: 0px"><a href="https://bryan.pipedot.org/">Bryan Beicker</a></div><div style="font-size: 8pt; color: #666666">2014-05-12 12:45</div></td>');
	writeln('					<td style="padding: 2px; vertical-align: top"><table style="float: right"><tr><td style="padding: 0px"><div class="row_button" style="background-image: url(/images/plus-16.png)" title="Reset"></div></td><td style="font-weight: bolder; vertical-align: middle">12</td><td style="padding: 0px"><div class="row_button" style="background-image: url(/images/minus-16.png)" title="Reset"></div></td></tr></table></td>');
	writeln('				</tr>');
	writeln('			</table>');
	writeln('		</td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td style="padding-left: 8px; padding-right: 8px; padding-top: 4px; padding-bottom: 4px;">Pipecode is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.</td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td style="padding-left: 21px; padding-right: 8px; padding-top: 4px; padding-bottom: 4px;"><img src="/pub/2014/05/31/i29.640x360.jpg"/></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td style="padding-left: 10px; padding-right: 8px; padding-top: 4px; padding-bottom: 8px;"><table style="width: 100%"><tr><td style="font-weight: bolder; padding: 0px;"><a href="">123 comments</a></td><td style="padding: 0px; font-size: 8pt; color: #666666; text-align: right">#pizza</td><td style="width: 24px; padding: 0px; padding-left: 4px;"><div class="row_button" style="background-image: url(/images/gear-16.png)" title="Reset"></div></td></tr></table></td>');
	writeln('	</tr>');
	writeln('</table>');
}


function print_card_large($a)
{
	writeln('<table class="card" style="width: 1030px; border: 1px solid #9f9f9f; border-radius: 10px; border-collapse: separate; border-spacing: 0px; margin: 4px; background-color: #ffffff">');
	writeln('	<tr>');
	writeln('		<td style="padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 2px;">');
	writeln('			<table style="width: 100%">');
	writeln('				<tr>');
	writeln('					<td style="padding: 2px;"><img style="width: 32px; border-radius: 4px;" src="/pub/profile/bryan.png"/></td>');
	writeln('					<td style="padding: 0px; padding-left: 8px; width: 100%;"><div style="font-weight: bolder; font-size: 10pt; padding-top: 0px">Bryan Beicker</div><div style="font-size: 8pt; color: #666666">2014-05-12 12:45</div></td>');
	writeln('					<td style="padding: 2px; vertical-align: top"><table style="float: right"><tr><td style="padding: 0px"><div class="row_button" style="background-image: url(/images/plus-16.png)" title="Reset"></div></td><td style="font-weight: bolder; vertical-align: middle">12</td><td style="padding: 0px"><div class="row_button" style="background-image: url(/images/minus-16.png)" title="Reset"></div></td></tr></table></td>');
	writeln('				</tr>');
	writeln('			</table>');
	writeln('		</td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td style="padding-left: 10px; padding-right: 10px; padding-top: 4px; padding-bottom: 8px;">');
	writeln('			<table style="width: 100%">');
	writeln('				<tr>');
	writeln('					<td style="padding-left: 0px; padding-right: 4px; padding-top: 0px; padding-bottom: 0px;"><img src="/pub/2014/05/31/i29.640x360.jpg"/></td>');
	writeln('					<td style="padding-left: 4px; padding-right: 0px; padding-top: 0px; padding-bottom: 0px; vertical-align: top;">Pipecode is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.</td>');
	writeln('				</tr>');
	writeln('			</table>');
	writeln('		</td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td style="padding-left: 10px; padding-right: 8px; padding-top: 4px; padding-bottom: 8px;"><table style="width: 100%"><tr><td style="font-weight: bolder; padding: 0px;"><a href="">123 comments</a></td><td style="padding: 0px; font-size: 8pt; color: #666666; text-align: right">#pizza</td><td style="width: 24px; padding: 0px; padding-left: 4px;"><div class="row_button" style="background-image: url(/images/gear-16.png)" title="Reset"></div></td></tr></table></td>');
	writeln('	</tr>');
	writeln('</table>');
}
*/

function slurp_title($url)
{
	$u = parse_url($url);
	if (!array_key_exists("host", $u)) {
		return false;
	}

	$data = http_slurp($url);
	if ($data === false) {
		return false;
	}

	$beg = stripos($data, "<title>");
	$end = stripos($data, "</title>", $beg);
	if ($beg === false || $end === false || $end < $beg) {
		return $u["host"];
	}
	$title = substr($data, $beg + 7, $end - $beg - 7);

        $title = html_entity_decode($title, ENT_QUOTES);
        $title = html_entity_decode($title, ENT_QUOTES);
        $title = clean_unicode($title);
        $title = htmlspecialchars($title);

	return $title;
}

