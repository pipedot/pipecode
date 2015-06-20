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

include("render.php");

$ip = urldecode($s2);
if (!string_uses($ip, "[0-9].:abcdef")) {
	fatal("Invalid ip address");
}
$ban_ip = db_get_rec("ban_ip", $ip);

print_header("Banned IP Address");
beg_main();

writeln('<h1>Banned IP Address</h1>');

beg_tab();
writeln('	<tr>');
writeln('		<td>IP Address</td>');
writeln('		<td class="right">' . $ip . '</td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td>Time</td>');
writeln('		<td class="right">' . date("Y-m-d H:i", $ban_ip["time"]) . '</td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td>Editor</td>');
writeln('		<td class="right">' . user_link($ban_ip["zid"], ["tag" => true]) . '</td>');
writeln('	</tr>');
end_tab();

writeln('<h2>Example Comment</h2>');

$comment = db_get_rec("comment", $ban_ip["short_id"]);

print render_comment($comment["subject"], $comment["zid"], $comment["publish_time"], $comment["comment_id"], $comment["body"], 0, "", "", $comment["junk_status"]);
writeln('</div>');
writeln('</article>');

if ($auth_user["admin"] || $auth_user["editor"]) {
	beg_form();
	box_right("Remove Ban");
	end_form();
}

end_main();
print_footer();
