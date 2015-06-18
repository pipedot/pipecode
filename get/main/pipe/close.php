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

require_editor();

$pipe = item_request(TYPE_PIPE);

print_header("Close Submission");
beg_main();
beg_form();
writeln("<h1>Close Submission</h1>");

writeln('<p>Are you sure you want to close this submission? The article will no longer show in the pipe, voting will be disabled, and comments will be locked.</p>');
writeln('<h2>Reason</h2>');
writeln('<p>Give a short reason for closing the article.</p>');
writeln('<input name="reason" type="text" len="50" required>');

box_left("Close");

end_form();
end_main();
print_footer();
