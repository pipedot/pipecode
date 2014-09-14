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

print_header("Icons");
beg_main();

writeln('<table class="fill">');
writeln('<tr>');
writeln('<td>');

$a = fs_dir("$doc_root/www/images");
for ($i = 0; $i < count($a); $i++) {
	if (fs_ext($a[$i]) == "png" && string_has($a[$i], "-64") && $a[$i] != "logo-top-64.png") {
		writeln('<div class="tbox">');
		writeln('	<img src="/images/' . $a[$i] . '"/>');
		writeln('	' . substr($a[$i], 0, -7));
		writeln('</div>');
	}
}

writeln('</td>');
writeln('</tr>');
writeln('</table>');

end_main();
print_footer();
