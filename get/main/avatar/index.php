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

$spinner[] = ["name" => "Avatar", "link" => "/avatar/"];

print_header(["title" => "Avatars"]);

$items_per_page = 100;
list($item_start, $page_footer) = page_footer("select count(distinct zid) as item_count from user_conf", $items_per_page);

$row = sql("select distinct zid from user_conf order by zid limit $item_start, $items_per_page");
writeln('<div class="gallery">');
foreach ($row as $avatar) {
	$zid = $avatar["zid"];
	$avatar_id = avatar_id($zid);
	$avatar_code = crypt_crockford_encode($avatar_id);
	writeln('	<a href="' . $avatar_code . '"><img alt="' . $zid . '" class="thumb" src="' . $avatar_code . '-256.jpg" title="' . $zid . '"></a>');
}
writeln('</div>');

writeln($page_footer);

print_footer();
