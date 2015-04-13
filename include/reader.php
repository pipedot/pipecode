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


function print_reader_nav()
{
	global $auth_zid;

	writeln('<nav class="reader">');

	dict_beg();
	dict_row('<a class="icon-16 reader-16" href="/reader/">All</a>', '');
	$feed_row = sql("select feed_id, name, slug from reader_user where zid = ? and topic_id = 0 order by name", $auth_zid);
	for ($f = 0; $f < count($feed_row); $f++) {
		dict_row('<a class="favicon-16" href="/reader/' . $feed_row[$f]["slug"] . '" style="background-image: url(/pub/favicon/' . crypt_crockford_encode($feed_row[$f]["feed_id"]) . '.png)">' . $feed_row[$f]["name"] . '</a>', '');
	}
	dict_end();

	$topic_row = sql("select topic_id, icon, name, slug from reader_topic where zid = ? order by name", $auth_zid);
	for ($t = 0; $t < count($topic_row); $t++) {
		dict_beg();
		dict_row('<a class="icon-16 ' . $topic_row[$t]["icon"] . '-16" href="/reader/topic/' . $topic_row[$t]["slug"] . '">' . $topic_row[$t]["name"] . '</a>', '');
		$feed_row = sql("select feed_id, name, slug from reader_user where zid = ? and topic_id = ? order by name", $auth_zid, $topic_row[$t]["topic_id"]);
		for ($f = 0; $f < count($feed_row); $f++) {
			dict_row('<a class="favicon-16" href="/reader/' . $feed_row[$f]["slug"] . '" style="background-image: url(/pub/favicon/' . crypt_crockford_encode($feed_row[$f]["feed_id"]) . '.png)">' . $feed_row[$f]["name"] . '</a>', '');
		}
		dict_end();
	}

	writeln('</nav>');
}
