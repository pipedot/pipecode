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

if (!$auth_user["admin"] && !$auth_user["editor"]) {
	die("not an editor or admin");
}

print_header("Menu");
beg_main("dual-table");

writeln('<div class="dual-left">');

beg_tab();
print_row(array("caption" => "Ban IP", "description" => "Manage banned IP addresses", "icon" => "error", "link" => "/ban_ip/"));
print_row(array("caption" => "Ban User", "description" => "Manage banned users", "icon" => "users", "link" => "/ban_user/"));
print_row(array("caption" => "Junk", "description" => "Mark junk messages", "icon" => "junk", "link" => "/junk/"));
if ($auth_user["admin"]) {
	print_row(array("caption" => "Links", "description" => "Manage server links", "icon" => "workgroup", "link" => "/link/"));
}
end_tab();

writeln('</div>');
writeln('<div class="dual_right">');

beg_tab();
if ($auth_user["admin"]) {
	print_row(array("caption" => "Pages", "description" => "Manage static pages", "icon" => "html", "link" => "/page/"));
}
print_row(array("caption" => "Poll", "description" => "Create a new poll", "icon" => "heart", "link" => "/poll/create"));
if ($auth_user["admin"]) {
	print_row(array("caption" => "Settings", "description" => "Configure the server settings", "icon" => "tools", "link" => "settings"));
	print_row(array("caption" => "Story Topics", "description" => "Add and remove story topics", "icon" => "chat", "link" => "/topic/list"));
	print_row(array("caption" => "Feed Topics", "description" => "Add and remove feed topics", "icon" => "news", "link" => "/feed/topic/list"));
}
end_tab();

writeln('</div>');

end_main();
print_footer();
