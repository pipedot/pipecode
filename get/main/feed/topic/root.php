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

if (!string_uses($s3, "[a-z]")) {
	fatal("Invalid topic");
}
$topic = db_get_rec("feed_topic", ["slug" => $s3]);

$spinner[] = ["name" => "Feed", "link" => "/feed/"];
$spinner[] = ["name" => "Topic", "link" => "/feed/topic/"];
$spinner[] = ["name" => $topic["name"], "link" => "/feed/topic/" . $topic["slug"]];

print_header();

dict_beg();
$list = db_get_list("feed", "title", ["topic_id" => $topic["topic_id"]]);
$k = array_keys($list);
for ($i = 0; $i < count($list); $i++) {
	$feed = $list[$k[$i]];
	$short_code = crypt_crockford_encode($feed["feed_id"]);
	if ($feed["title"] == "") {
		$title = get_text('(none)');
	} else {
		$title = $feed["title"];
	}
	if (fs_is_file("$doc_root/www/pub/favicon/$short_code.png")) {
		$icon = ' style="background-image: url(/pub/favicon/' . $short_code . '.png)"';
	} else {
		$icon = "";
	}
	dict_row('<a class="favicon-16"' . $icon . ' href="/feed/' . $feed["slug"] . '">' . $title . '</a>', '<a class="icon-16 plus-16" href="/feed/' . $feed["slug"] . '/add">' . get_text('Add') . '</a>');
}
dict_end();

print_footer();
