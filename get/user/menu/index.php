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

print_header("Menu");
beg_main("dual_table");

//writeln('<div class="dual_table">');
writeln('<div class="dual_left">');

beg_tab();
print_row(array("caption" => "Journal", "description" => "View your journal", "icon" => "notepad", "link" => "/journal/"));
print_row(array("caption" => "Feed", "description" => "View news feeds", "icon" => "news", "link" => "/feed/"));
print_row(array("caption" => "Stream", "description" => "View news stream", "icon" => "internet", "link" => "/stream/"));
print_row(array("caption" => "Comments", "description" => "View your past comments", "icon" => "chat", "link" => "/comments"));
end_tab();

writeln('</div>');
writeln('<div class="dual_right">');

beg_tab();
print_row(array("caption" => "Mail", "description" => "Send and receive mail", "icon" => "mail", "link" => "/mail/"));
print_row(array("caption" => "Profile Settings", "description" => "Configure your account settings", "icon" => "tools", "link" => "/profile/"));
print_row(array("caption" => "Profile Picture", "description" => "Upload a new profile image", "icon" => "picture", "link" => "/profile/picture"));
print_row(array("caption" => "Karma", "description" => "Monitor your karma rating", "icon" => "face_smile", "link" => "/karma/"));
end_tab();

writeln('</div>');
//writeln('</div>');

end_main();
print_footer();
