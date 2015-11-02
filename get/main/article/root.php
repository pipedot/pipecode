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

include("story.php");

$article = item_request(TYPE_ARTICLE);
$article["type_id"] = TYPE_ARTICLE;
$article["type"] = "article";
$short_code = crypt_crockford_encode($article["article_id"]);

print_header($article["title"]);
beg_main();

print_news_large($article);

writeln('<div class="external-title">' . get_text('External Content') . '</div>');
writeln('<table class="external-table">');
if ($article["feed_id"] > 0) {
	$feed = db_get_rec("feed", $article["feed_id"]);

	writeln('	<tr>');
	writeln('		<td>' . get_text('Source') . '</td>');
	writeln('		<td>' . get_text('RSS or Atom Feed') . '</td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td>' . get_text('Feed Location') . '</td>');
	writeln('		<td><a href="' . $feed["uri"] . '">' . $feed["uri"] . '</a></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td>' . get_text('Feed Title') . '</td>');
	writeln('		<td>' . $feed["title"] . '</td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td>' . get_text('Feed Link') . '</td>');
	writeln('		<td><a href="' . $feed["link"] . '">' . $feed["link"] . '</a></td>');
	writeln('	</tr>');
	if ($feed["copyright"] != "") {
		writeln('	<tr>');
		writeln('		<td>' . get_text('Feed Copyright') . '</td>');
		writeln('		<td>' . $feed["copyright"] . '</td>');
		writeln('	</tr>');
	}
}
writeln('</table>');

print_comments(TYPE_ARTICLE, $article);

end_main();
print_footer();
