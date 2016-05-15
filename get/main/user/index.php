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

$spinner[] = ["name" => "User", "link" => "/user/"];

print_header(["title" => "Users"]);

$items_per_page = 100;
list($item_start, $page_footer) = page_footer("select count(distinct zid) as item_count from user_conf", $items_per_page);

$row = sql("select distinct zid from user_conf order by zid limit $item_start, $items_per_page");
beg_tab();
for ($i = 0; $i < count($row); $i++) {
	$zid = $row[$i]["zid"];
	$avatar = avatar_picture($zid, 64);
	list($user, $domain) = explode("@", $zid);
	writeln('	<tr>');
	writeln('		<td class="hover">');
	writeln('			<a href="' . str_replace("@", ".", $zid) . '">');
	writeln('			<dl class="dl-32" style="background-image: url(' . $avatar . ')">');
	writeln('				<dt>' . $user . '</dt>');
	writeln('				<dd>' . $domain . '</dd>');
	writeln('			</dl>');
	writeln('			</a>');
	writeln('		</td>');
	writeln('	</tr>');
}
end_tab();

writeln($page_footer);

print_footer();
