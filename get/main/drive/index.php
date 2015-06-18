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

require_admin();

print_header("Drive");
beg_main();

$row = sql("select count(size) as thumb_count, sum(size) as thumb_total from thumb inner join drive_data on thumb.hash = drive_data.hash");
$thumb_count = $row[0]["thumb_count"];
$thumb_total = $row[0]["thumb_total"];

$row = sql("select count(size) as cache_count, sum(size) as cache_total from cache inner join drive_data on cache.data_hash = drive_data.hash");
$cache_count = $row[0]["cache_count"];
$cache_total = $row[0]["cache_total"];

$row = sql("select count(size) as avatar_count, sum(size) as avatar_total from avatar inner join drive_data on avatar.hash_64 = drive_data.hash");
$avatar_count = $row[0]["avatar_count"];
$avatar_64_total = $row[0]["avatar_total"];
$row = sql("select sum(size) as avatar_total from avatar inner join drive_data on avatar.hash_128 = drive_data.hash");
$avatar_128_total = $row[0]["avatar_total"];
$row = sql("select sum(size) as avatar_total from avatar inner join drive_data on avatar.hash_256 = drive_data.hash");
$avatar_256_total = $row[0]["avatar_total"];

dict_beg();
dict_row("Avatar Count", number_format($avatar_count));
dict_row("Avatar Size (64 x 64)", sys_format_size($avatar_64_total));
dict_row("Avatar Size (128 x 128)", sys_format_size($avatar_128_total));
dict_row("Avatar Size (256 x 256)", sys_format_size($avatar_256_total));
dict_end();

dict_beg();
dict_row("Cache Count", number_format($cache_count));
dict_row("Cache Size", sys_format_size($cache_total));
dict_end();

dict_beg();
dict_row("Thumbnail Count", number_format($thumb_count));
dict_row("Thumbnail Size", sys_format_size($thumb_total));
dict_end();

end_main();
print_footer();
