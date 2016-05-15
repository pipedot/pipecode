<?
//
// Pipecode - distributed social network
// Copyright (C) 2014-2016 Bryan Beicker <bryan@pipedot.org>
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

$spinner[] = ["name" => "Tools", "link" => "/tools/"];

print_header();

menu_beg();
menu_row(["caption" => "Ban IP", "description" => "Manage banned IP addresses", "icon" => "error", "link" => "/ban_ip/"]);
menu_row(["caption" => "Ban User", "description" => "Manage banned users", "icon" => "devil", "link" => "/ban_user/"]);
menu_row(["caption" => "Drive", "description" => "View storage information", "icon" => "drive", "link" => "/drive/", "visible" => $auth_user["admin"]]);
menu_row(["caption" => "Feed Topics", "description" => "Add and remove feed topics", "icon" => "reader", "link" => "/feed/topic/list", "visible" => $auth_user["admin"]]);
menu_row(["caption" => "Footer Links", "description" => "Edit the links at the bottom of the page", "icon" => "link", "link" => "/footer_link/", "visible" => $auth_user["admin"]]);
menu_row(["caption" => "Junk", "description" => "Mark junk messages", "icon" => "junk", "link" => "/junk/", "visible" => $auth_user["editor"]]);
menu_row(["caption" => "Pages", "description" => "Manage static pages", "icon" => "html", "link" => "/page/", "visible" => $auth_user["admin"]]);
menu_row(["caption" => "Poll", "description" => "Create a new poll", "icon" => "heart", "link" => "/poll/create", "visible" => $auth_user["editor"]]);
menu_row(["caption" => "Settings", "description" => "Configure the server settings", "icon" => "tools", "link" => "settings", "visible" => $auth_user["admin"]]);
menu_row(["caption" => "Story Topics", "description" => "Add and remove story topics", "icon" => "news", "link" => "/topic/list", "visible" => $auth_user["admin"]]);
menu_row(["caption" => "Tools", "description" => "Tools for short codes", "icon" => "hardhat", "link" => "short"]);
menu_row(["caption" => "Users", "description" => "List local users", "icon" => "users", "link" => "/user/"]);
menu_end();

print_footer();
