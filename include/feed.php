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

include("$top_root/lib/simplepie/simplepie.php");


function download_feed($uri)
{
	//$feed = db_get_rec("feed", $fid);

	//print "feed [$fid] uri [" . $feed["uri"] . "] ";

	//$data = @file_get_contents($feed["uri"]);
	$data = @file_get_contents($uri);
	//print "len [" . strlen($data) . "] ";

	return $data;
}


function save_feed($fid, $data)
{
	$feed = db_get_rec("feed", $fid);

	//print "feed [$fid] uri [" . $feed["uri"] . "] ";

	//$data = @file_get_contents($feed["uri"]);
	//print "len [" . strlen($data) . "] ";

	$sp = new SimplePie();
	$sp->set_raw_data($data);
	//$sp->set_feed_url($feed["uri"]);
	$sp->init();

	$title = $sp->get_title();
	$link = get_feed_link($sp, $feed["uri"]);
	//$link = $sp->get_permalink();
	//if ($link == "") {
	//	$a = explode("/", $feed["uri"]);
	//	if (count($a) >= 3) {
	//		$link = $a[0] . "//" . $a[2] . "/";
	//	}
	//}

	$feed["title"] = $title;
	$feed["link"] = $link;
	$feed["time"] = time();
	db_set_rec("feed", $feed);

	//print "title [$title] link [$link] ";

	//writeln("link [" . $link . "]<br/>");
	//writeln("title [" . $title . "]<br/>");
	run_sql("delete from feed_item where fid = ?", array($fid));
	$count = 0;

	foreach ($sp->get_items(0, 8) as $item) {
		$item_link = $item->get_permalink();
		if (empty($item_link)) {
			$item_link = $link;
		}
		//$item_title = html_entity_decode($item->get_title());
		$item_title = $item->get_title();
		//$item_description = $item->get_description();
		$item_time = $item->get_date("U");
		if (empty($item_time)) {
			$item_time = time();
		}

		if (!empty($item_title)) {
			//writeln("link [$item_link] title [$item_title] description [$item_description]<br/>");
			//writeln("link [$item_link] title [$item_title] time [$item_time]<br/>");

			$feed_item = array();
			$feed_item["fid"] = $fid;
			$feed_item["title"] = $item_title;
			$feed_item["link"] = $item_link;
			$feed_item["time"] = $item_time;
			db_set_rec("feed_item", $feed_item);
			$count++;
		}
	}

	//print "items [$count]\n";
}


function get_feed_link($sp, $uri)
{
	$link = $sp->get_permalink();
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
		return $feed["fid"];
	}

	$data = download_feed($uri);
	$sp = new SimplePie();
	$sp->set_raw_data($data);
	$sp->init();
	$title = $sp->get_title();
	$link = get_feed_link($sp, $uri);
	$count = $sp->get_item_quantity();

	if (strlen($title) == 0 || $count == 0) {
		die("unable to parse feed [$uri]");
		//die("unable to parse feed [$uri] data [$data]");
	}

	$feed = array();
	$feed["fid"] = 0;
	$feed["time"] = time();
	$feed["uri"] = $uri;
	$feed["title"] = $title;
	$feed["link"] = $link;
	db_set_rec("feed", $feed);

	$feed = db_get_rec("feed", array("uri" => $uri));
	save_feed($feed["fid"], $data);

	return $feed["fid"];
}


function clean_feed_title($title)
{
	$title = str_replace("&amp;", "&", $title);

	return $title;
}


function print_feed_page($zid)
{
	writeln('<table style="width: 100%">');
	writeln('	<tr>');

	for ($c = 0; $c < 3; $c++) {
		writeln('		<td class="feed_box">');

		$row = run_sql("select fid from feed_user where zid = ? and col = ? order by pos", array($zid, $c));
		for ($f = 0; $f < count($row); $f++) {
			$feed = db_get_rec("feed", $row[$f]["fid"]);

			writeln('			<div class="feed_title"><a href="' . $feed["link"] . '">' . $feed["title"] . '</a></div>');
			writeln('			<div class="feed_body">');
			$items = db_get_list("feed_item", "time desc", array("fid" => $feed["fid"]));
			$item_keys = array_keys($items);
			for ($j = 0; $j < count($items); $j++) {
				$item = $items[$item_keys[$j]];
				writeln('				<div><a href="' . $item["link"] . '">' . clean_feed_title($item["title"]) . '</a></div>');
			}
			writeln('			</div>');
		}
		writeln('		</td>');
	}

	writeln('	</tr>');
	writeln('</table>');
}
