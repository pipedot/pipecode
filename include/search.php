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

function search_box($needle = "", $haystack = "comments")
{
	beg_form("", "get");
	writeln('<table class="round">');
	writeln('	<tr>');
	writeln('		<td rowspan="2" style="width: 64px"><img alt="Search" src="/images/magnifier-64.png"/></td>');
	writeln('		<td style="width: 100%; vertical-align: bottom"><input type="search" name="needle" value="' . $needle . '" required="required"/></td>');
	writeln('		<td style="width: 64px; vertical-align: bottom"><input type="submit" value="Search"/></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td colspan="2" style="vertical-align: top">');
	if ($haystack == "comments") {
		writeln('			<label><input type="radio" name="haystack" value="comments" checked="checked"/>Comments</label>');
	} else {
		writeln('			<label><input type="radio" name="haystack" value="comments"/>Comments</label>');
	}
	if ($haystack == "stories") {
		writeln('			<label><input type="radio" name="haystack" value="stories" checked="checked"/>Stories</label>');
	} else {
		writeln('			<label><input type="radio" name="haystack" value="stories"/>Stories</label>');
	}
	if ($haystack == "pipe") {
		writeln('			<label><input type="radio" name="haystack" value="pipe" checked="checked"/>Pipe</label>');
	} else {
		writeln('			<label><input type="radio" name="haystack" value="pipe"/>Pipe</label>');
	}
	if ($haystack == "polls") {
		writeln('			<label><input type="radio" name="haystack" value="polls" checked="checked"/>Polls</label>');
	} else {
		writeln('			<label><input type="radio" name="haystack" value="polls"/>Polls</label>');
	}
	writeln('		</td>');
	writeln('	</tr>');
	writeln('</table>');
	end_form();
}


function search_result($title, $link, $zid, $time, $body)
{
	global $server_name;
	global $protocol;

	$date = date("Y-m-d H:i", $time);
	$by = user_page_link($zid, true);

	writeln("<article>");
	writeln("	<h1><a href=\"$link\">$title</a></h1>");
	writeln("	<h2>$protocol://$server_name$link</h2>");
	writeln("	<h3>by $by on $date</h3>");
	writeln("	<p>$body</p>");
	writeln("</article>");
}

