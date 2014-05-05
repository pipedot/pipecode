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

if ($zid != $auth_zid) {
	die("not your page");
}

$fid = http_get_int("fid");
$feed = db_get_rec("feed", $fid);

if (http_post()) {
	db_del_rec("feed_user", array("zid" => $auth_zid, "fid" => $fid));

	$row = run_sql("select count(zid) as user_count from feed_user where fid = ?", array($fid));
	$count = $row[0]["user_count"];
	if ($count == 0) {
		run_sql("delete from feed_item where fid = ?", array($fid));
		run_sql("delete from feed where fid = ?", array($fid));
	}

	header("Location: edit");
	die();
}

print_header();

writeln('<form method="post">');
writeln('<h1>Remove Feed</h1>');
writeln('<p>Are you sure you want to remove <b>' . $feed["title"] . '</b> from your page?</p>');
writeln('<input type="submit" value="Remove"/>');
writeln('</form>');

print_footer();
