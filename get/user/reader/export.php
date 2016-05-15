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

require_mine();

header('Content-Type: text/xml');
header('Content-Disposition: attachment; filename="reader.opml"');
//header_text();

writeln('<?xml version="1.0" encoding="UTF-8"?>');
writeln('<opml version="2.0">');
writeln('	<head>');
writeln('		<title>' . $auth_zid . '</title>');
writeln('	</head>');
writeln('	<body>');

$topic_row = sql("select topic_id, name from reader_topic where zid = ? order by name", $auth_zid);
for ($t = 0; $t < count($topic_row); $t++) {
	writeln('		<outline text="' . $topic_row[$t]["name"] . '" title="' . $topic_row[$t]["name"] . '">');
	$feed_row = sql("select reader_user.name, title, uri, link from reader_user inner join feed on reader_user.feed_id = feed.feed_id where zid = ? and reader_user.topic_id = ? order by reader_user.name", $auth_zid, $topic_row[$t]["topic_id"]);
	for ($f = 0; $f < count($feed_row); $f++) {
		writeln('			<outline type="rss" text="' . $feed_row[$f]["name"] . '" title="' . $feed_row[$f]["title"] . '" xmlUrl="' . $feed_row[$f]["uri"] . '" htmlUrl="' . $feed_row[$f]["link"] . '"/>');
	}
	writeln('		</outline>');
}
writeln('	</body>');
writeln('</opml>');
