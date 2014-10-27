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

print_header("Source Code");
beg_main();

writeln('<h1>Source Code</h1>');
writeln('<p>This source code of this site is licensed under the GNU Affero General Public License</p>');

$body = fs_slurp("$doc_root/license.html");
$beg = strpos($body, "<body>") + 6;
$end = strpos($body, "</body>");
$body = substr($body, $beg, $end - $beg);

beg_tab("License");
writeln('<tr>');
writeln('<td>');
writeln($body);
writeln('</td>');
writeln('</tr>');
end_tab();

$tarball = substr($server_name, 0, strpos($server_name, ".")) . "-" . gmdate("Y-m-d") . ".tar.gz";

beg_tab("Download", array("colspan" => 2));
writeln('	<tr>');
writeln('		<td>Offical Webpage</td>');
writeln('		<td class="right"><a href="https://pipecode.org/">https://pipecode.org/</a></td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td>GitHub Repository</td>');
writeln('		<td class="right"><a href="https://github.com/pipedot/pipecode">https://github.com/pipedot/pipecode</a></td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td>This Site</td>');
writeln('		<td class="right"><a href="/download" rel="nofollow">' . $tarball . '</a></td>');
writeln('	</tr>');
end_tab();

end_main();
print_footer();

