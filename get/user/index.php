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

print_header();
beg_main();

menu_beg();
menu_row(["caption" => "Avatar", "description" => "Change your avatar", "icon" => "user", "link" => "/avatar/", "visible" => $mine]);
menu_row(["caption" => "Comments", "description" => "View past comments", "icon" => "chat", "link" => "/comments"]);
menu_row(["caption" => "Contacts", "description" => "Edit your address book", "icon" => "binder", "link" => "/contact/", "visible" => $mine]);
//menu_row(["caption" => "Drive", "description" => "Browse your files", "icon" => "drive", "link" => "/drive/", "visible" => $mine]);
menu_row(["caption" => "Feed", "description" => "Show news page", "icon" => "html", "link" => "/feed/", "visible" => $mine]);
menu_row(["caption" => "Journal", "description" => "View journal entries", "icon" => "notepad", "link" => "/journal/"]);
menu_row(["caption" => "Karma", "description" => "Show karma rating", "icon" => "face-smile", "link" => "/karma/"]);
menu_row(["caption" => "Login", "description" => "Monitor your active sessions", "icon" => "lock", "link" => "/login/", "visible" => $mine]);
menu_row(["caption" => "Mail", "description" => "Send and receive mail", "icon" => "mail", "link" => "/mail/", "visible" => $mine]);
menu_row(["caption" => "Notifications", "description" => "View your notification messages", "icon" => "bulb", "link" => "/notification/", "visible" => $mine]);
menu_row(["caption" => "Reader", "description" => "Read news feeds", "icon" => "reader", "link" => "/reader/", "visible" => $mine]);
menu_row(["caption" => "Settings", "description" => "Configure your account settings", "icon" => "tools", "link" => "/settings", "visible" => $mine]);
menu_row(["caption" => "Stream", "description" => "Browse news stream", "icon" => "internet", "link" => "/stream/"]);
menu_row(["caption" => "Submissions", "description" => "View story submissions", "icon" => "news", "link" => "/submissions"]);
menu_row(["caption" => "Summary", "description" => "View user overview", "icon" => "spreadsheet", "link" => "/summary"]);
menu_end();

end_main();
print_footer();
