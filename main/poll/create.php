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

print_header("Create Poll");

writeln('<table class="fill">');
writeln('<tr>');
writeln('<td class="left_col">');
print_left_bar("main", "poll");
writeln('</td>');
writeln('<td class="fill">');

writeln('<h1>Create Poll</h1>');

beg_tab("Question");
print_row(array("caption" => "Text", "text_key" => "question"));
print_row(array("caption" => "Type", "option_key" => "hide_threshold", "option_keys" => array(0, 1), "option_list" => array("Multiple Choice", "Approval Voting")));
end_tab();

beg_tab("Answers", array("colspan" => 2));
writeln('	<tr>');
writeln('		<td><input type="text"/></td>');
writeln('		<td style="text-align: right"><a href="delete" class="icon_16" style="background-image: url(/images/remove-16.png)">Remove</a></td>');
writeln('	</tr>');
end_tab();

writeln('<div class="right"><a href="add" class="icon_16" style="background-image: url(/images/add-16.png)">Add</a></div>');

writeln('		</td>');
writeln('	</tr>');
writeln('</table>');

print_footer();
