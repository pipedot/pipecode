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

if ($zid != $auth_zid) {
	die("not your page");
}

$col = http_get_int("col");
if ($col < 0 || $col > 2) {
	die("invalid col [$col]");
}

print_header();
print_left_bar("user", "feed");
beg_main("cell");
beg_form();

writeln('<div class="dialog_title">Add Feed</div>');
writeln('<div class="dialog_body">');

writeln('<table style="width: 100%">');
writeln('	<tr>');
writeln('		<td style="width: 120px">Use existing feed:</td>');
writeln('		<td>');
writeln('			<select name="fid" style="width: 100%">');
writeln('				<option value="0">(select feed)</option>');

$existing = array();
$row = sql("select fid from feed_user where zid = ?", $auth_zid);
for ($i = 0; $i < count($row); $i++) {
	$existing[$row[$i]["fid"]] = 1;
}

$row = sql("select fid, title from feed order by title");
for ($i = 0; $i < count($row); $i++) {
	if (!array_key_exists($row[$i]["fid"], $existing)) {
		writeln('				<option value="' . $row[$i]["fid"] . '">' . $row[$i]["title"] . '</option>');
	}
}
writeln('			</select>');
writeln('		</td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td style="width: 120px">Or add new feed:</td>');
writeln('		<td><input name="uri" type="text" style="width: 100%"/></td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td colspan="2" class="right"><input type="submit" value="Add"/></td>');
writeln('	</tr>');
writeln('</table>');

writeln('</div>');

end_form();
end_main();
print_footer();
