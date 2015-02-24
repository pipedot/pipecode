<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
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

include("story.php");

$article = find_rec("article");
$short_code = crypt_crockford_encode($article["article_id"]);

print_header($article["title"]);
beg_main();

//writeln('<h1>' . $article["title"] . '</h1>');
//writeln('<article>');
//writeln($article["body"]);
//writeln('</article>');
print_article($article);

writeln('<div class="external_title">External Content</div>');
writeln('<table class="external_table">');
if ($article["feed_id"] > 0) {
	$feed = db_get_rec("feed", $article["feed_id"]);

	writeln('	<tr>');
	writeln('		<td>Source</td>');
	writeln('		<td>RSS or Atom Feed</td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td>Feed Location</td>');
	writeln('		<td><a href="' . $feed["uri"] . '">' . $feed["uri"] . '</a></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td>Feed Title</td>');
	writeln('		<td>' . $feed["title"] . '</td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td>Feed Link</td>');
	writeln('		<td><a href="' . $feed["link"] . '">' . $feed["link"] . '</a></td>');
	writeln('	</tr>');
	if ($feed["copyright"] != "") {
		writeln('	<tr>');
		writeln('		<td>Feed Copyright</td>');
		writeln('		<td>' . $feed["copyright"] . '</td>');
		writeln('	</tr>');
	}
}
writeln('</table>');

//writeln('<div class="external_title">External Content</div>');
//if ($article["feed_id"] > 0) {
//	writeln("<div class=\"external_body\">This article comes from a RSS or Atom feed and is not part of $server_title.</div>");
//}
//writeln("<div class=\"external_body\">Copyright 2015 AnandTech</div>");

//writeln('<div class="balloon">');
//writeln('<h1>External Content</h1>');
//if ($article["feed_id"] > 0) {
//	writeln("<p>This article comes from a RSS or Atom feed and is not part of $server_title.</p>");
//}
//writeln('</div>');

end_main();
print_footer();
