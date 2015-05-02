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

print_header("Forgot Password");
writeln('<hr>');
beg_main();
beg_form();
writeln('<h1>Forgot Password?</h1>');

writeln('<table class="login">');
writeln('	<tr>');
writeln('		<td class="right">Username</td>');
writeln('		<td><input name="username" type="text"></td>');
writeln('	</tr>');
writeln('</table>');

box_left("Send");

end_form();
end_main();
print_footer();

