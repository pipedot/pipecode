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

if ($zid !== $auth_zid) {
	die("not your page");
}

$feed_id = http_get_int("feed_id");
$feed = db_get_rec("feed", $feed_id);

print_header();
beg_main();
beg_form();

writeln('<h1>Remove Feed</h1>');
writeln('<p>Are you sure you want to remove <b>' . $feed["title"] . '</b> from your page?</p>');

left_box("Remove");

end_form();
end_main();
print_footer();
