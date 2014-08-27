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

if (!$auth_user["admin"]) {
	die("not an admin");
}

print_header("SoylentNews");
beg_main("dual_table");

writeln('<div class="dual_left">');

beg_tab();
print_row(array("caption" => "User", "description" => "Import a user", "icon" => "users", "link" => "user"));
print_row(array("caption" => "Story", "description" => "Import a story", "icon" => "news", "link" => "story"));
print_row(array("caption" => "Comment", "description" => "Import a comment", "icon" => "chat", "link" => "comment"));
end_tab();

writeln('</div>');
writeln('<div class="dual_right">');

beg_tab();
print_row(array("caption" => "Update Stories", "description" => "Check for new stoies", "icon" => "update", "link" => "update_stories"));
print_row(array("caption" => "Update Comments", "description" => "Check for new comments", "icon" => "update", "link" => "update_comments"));
print_row(array("caption" => "Fix Comments", "description" => "Set parents for non-root comments", "icon" => "tools", "link" => "fix_comments"));
print_row(array("caption" => "Log", "description" => "View import log", "icon" => "notepad", "link" => "log"));
end_tab();

writeln('</div>');

end_main();
print_footer();
