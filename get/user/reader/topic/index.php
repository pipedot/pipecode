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

include("feed.php");

require_mine();

$spinner[] = ["name" => "Reader", "link" => "/reader/"];
$spinner[] = ["name" => "Topic", "link" => "/reader/topic/"];

print_header();

writeln('<h1>' . get_text('Edit Topics') . '</h1>');

dict_beg();
$feed_row = sql("select feed_id, name, slug from reader_user where zid = ? and topic_id = 0 order by name", $auth_zid);
for ($f = 0; $f < count($feed_row); $f++) {
	//dict_row('<a class="favicon-16" href="/reader/' . $feed_row[$f]["slug"] . '" style="background-image: url(/pub/favicon/' . crypt_crockford_encode($feed_row[$f]["feed_id"]) . '.png)">' . $feed_row[$f]["name"] . '</a>', '<a class="icon-16 notepad-16" href="/reader/' . $feed_row[$f]["slug"] . '/edit">Edit</a> | <a class="icon-16 minus-16" href="/reader/' . $feed_row[$f]["slug"] . '/remove">Remove</a>');
	dict_row('<a class="favicon-16" href="/reader/' . $feed_row[$f]["slug"] . '/edit" style="background-image: url(/pub/favicon/' . crypt_crockford_encode($feed_row[$f]["feed_id"]) . '.png)">' . $feed_row[$f]["name"] . '</a>', '<a class="icon-16 minus-16" href="/reader/' . $feed_row[$f]["slug"] . '/remove">' . get_text('Remove') . '</a>');
}
dict_end();

$topic_row = sql("select topic_id, icon, name, slug from reader_topic where zid = ? order by name", $auth_zid);
for ($t = 0; $t < count($topic_row); $t++) {
	dict_beg();
	//dict_row('<a class="icon-16 ' . $topic_row[$t]["icon"] . '-16" href="/reader/topic/' . $topic_row[$t]["slug"] . '">' . $topic_row[$t]["name"] . '</a>', '<a class="icon-16 notepad-16" href="/reader/topic/' . $topic_row[$t]["slug"] . '/edit">Edit</a> | <a class="icon-16 minus-16" href="/reader/topic/' . $topic_row[$t]["slug"] . '/remove">Remove</a>');
	dict_row('<a class="icon-16 ' . $topic_row[$t]["icon"] . '-16" href="/reader/topic/' . $topic_row[$t]["slug"] . '/edit">' . $topic_row[$t]["name"] . '</a>', '<a class="icon-16 minus-16" href="/reader/topic/' . $topic_row[$t]["slug"] . '/remove">' . get_text('Remove') . '</a>');
	$feed_row = sql("select feed_id, name, slug from reader_user where zid = ? and topic_id = ? order by name", $auth_zid, $topic_row[$t]["topic_id"]);
	for ($f = 0; $f < count($feed_row); $f++) {
		//dict_row('<a class="favicon-16" href="/reader/' . $feed_row[$f]["slug"] . '" style="background-image: url(/pub/favicon/' . crypt_crockford_encode($feed_row[$f]["feed_id"]) . '.png)">' . $feed_row[$f]["name"] . '</a>', '<a class="icon-16 notepad-16" href="/reader/' . $feed_row[$f]["slug"] . '/edit">Edit</a> | <a class="icon-16 minus-16" href="/reader/' . $feed_row[$f]["slug"] . '/remove">Remove</a>');
		dict_row('<a class="favicon-16" href="/reader/' . $feed_row[$f]["slug"] . '/edit" style="background-image: url(/pub/favicon/' . crypt_crockford_encode($feed_row[$f]["feed_id"]) . '.png)">' . $feed_row[$f]["name"] . '</a>', '<a class="icon-16 minus-16" href="/reader/' . $feed_row[$f]["slug"] . '/remove">' . get_text('Remove') . '</a>');
	}
	dict_end();
}

box_right('<a class="icon-16 feed-16" href="../add">' . get_text('Add Feed') . '</a> | <a class="icon-16 news-16" href="add">' . get_text('Add Topic') . '</a> | <a class="icon-16 opml-16" href="../export">' . get_text('Export') . '</a>');

print_footer();
