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

require_login();
require_mine();

print_header("Drive");
beg_main();

//$path = "/";
//writeln('<h1>' . $path . '</h1>');
print_drive_crumbs($path, $auth_zid);

//writeln('<div class="crumbs">');
//writeln('	<ul class="breadcrumb">');
//writeln('		<li><a href="/drive">Home</a></li>');
//writeln('		<li><a href="/drive/music/">Music</a></li>');
//writeln('		<li><a href="/drive/music/bush/">Brad Sucks</a></li>');
//writeln('	</ul>');
//writeln('</div>');

//writeln('<div class="triangle">test</div>');

print_drive_folder($path, $auth_zid);

box_right('<a class="icon-16 folder_new-16" href="?mkdir">Create Folder</a>');

end_main();
print_footer();
