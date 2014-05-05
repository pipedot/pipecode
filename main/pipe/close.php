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

$pid = $s2;
if (!string_uses($pid, "[0-9]")) {
	die("invalid pid [$pid]");
}

if (!$auth_user["editor"]) {
	die("you are not an editor");
}

if (http_post()) {
	$reason = http_post_string("reason", array("len" => 50, "valid" => "[a-z][A-Z][0-9]~!@#$%^*()_+-=[]\{}|;',./? "));

	$pipe = db_get_rec("pipe", $pid);
	if ($pipe["closed"]) {
		die("article already closed [$pid]");
	}

	$pipe["closed"] = 1;
	$pipe["editor"] = $auth_zid;
	$pipe["reason"] = $reason;
	db_set_rec("pipe", $pipe);

	header("Location: /pipe/$pid");
	die();
}

print_header("Close Submission");

writeln("<h1>Close Submission</h1>");

writeln('<form method="post">');
writeln('<p>Are you sure you want to close this submission? The article will no longer show in the pipe, voting will be disabled, and comments will be locked.</p>');
writeln('<h2>Reason</h2>');
writeln('<p>Give a short reason for closing the article.</p>');
writeln('<input name="reason" type="text" len="50" required="required"/>');
writeln('<input type="submit" value="Close"/>');
writeln('</form>');

print_footer();
