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
$pipe_code = $pipe["short_code"];

$spinner[] = ["name" => "Pipe", "link" => "/pipe/"];
$spinner[] = ["name" => $pipe["title"], "short" => $pipe_code, "link" => "/pipe/$pipe_code"];
$spinner[] = ["name" => "Close", "link" => "/pipe/$pipe_code/close"];

print_header(["title" => "Close Submission", "form" => true]);

writeln('<h1>' . get_text('Close Submission') . '</h1>');
writeln('<p>' . get_text('Are you sure you want to close this submission? The article will no longer show in the pipe and voting will be disabled.') . '</p>');

beg_tab();
print_row(array("caption" => "Reason", "text_key" => "reason", "required" => true));
end_tab();

box_right("Close");

print_footer(["form" => true]);
