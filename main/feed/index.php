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

include("feed.php");

print_header();

print_left_bar("main", "feed");
beg_main("cell");

$zid = "bryan@$server_name";
print_feed_page($zid);

if ($auth_zid == "") {
	writeln('<div style="text-align: center">This is a sample feed page. Login to create your own.</div>');
} else {
	writeln('<div style="text-align: center">This is a sample feed page. <a href="' . user_page_link($auth_zid) . 'feed/edit">Create</a> your own <a href="' . user_page_link($auth_zid) . '">homepage</a>.</div>');
}

end_main();
print_footer();
