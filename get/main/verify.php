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

require_https($https_enabled);

print_header("Verification Code");
writeln('<hr>');
beg_main();
beg_form("/reset", "get");
writeln('<h1>Verification Code</h1>');

writeln('<table class="login">');
writeln('	<tr>');
writeln('		<td>Code</td>');
writeln('		<td><input name="code" type="text" autofocus required></td>');
writeln('	</tr>');
writeln('</table>');

box_left("Verify");

end_form();
end_main();
print_footer();


