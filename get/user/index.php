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

print_header();
beg_main("dual-table");
writeln('<div class="dual-left">');

beg_tab();
if ($zid === $auth_zid) {
	print_row(array("caption" => "Avatar", "description" => "Change your avatar", "icon" => "user", "link" => "/avatar/"));
}
if ($zid === $auth_zid) {
	print_row(array("caption" => "Feed", "description" => "Show news page", "icon" => "news", "link" => "/feed/"));
}
print_row(array("caption" => "Karma", "description" => "Show karma rating", "icon" => "face-smile", "link" => "/karma/"));
if ($zid === $auth_zid) {
	print_row(array("caption" => "Profile Settings", "description" => "Configure your account settings", "icon" => "tools", "link" => "/profile/settings"));
} else {
	print_row(array("caption" => "Profile", "description" => "View public profile", "icon" => "users", "link" => "/profile/"));
}
print_row(array("caption" => "Stream", "description" => "Browse news stream", "icon" => "internet", "link" => "/stream/"));
end_tab();

writeln('</div>');
writeln('<div class="dual-right">');

beg_tab();
print_row(array("caption" => "Comments", "description" => "View past comments", "icon" => "chat", "link" => "/comments"));
print_row(array("caption" => "Journal", "description" => "View journal entries", "icon" => "notepad", "link" => "/journal/"));
if ($zid === $auth_zid) {
	print_row(array("caption" => "Mail", "description" => "Send and receive mail", "icon" => "mail", "link" => "/mail/"));
	print_row(array("caption" => "Reader", "description" => "Read news feeds", "icon" => "reader", "link" => "/reader/"));
	//print_row(array("caption" => "Drive", "description" => "Browse your files", "icon" => "drive", "link" => "/drive/"));
}
end_tab();

writeln('</div>');
end_main();
print_footer();

