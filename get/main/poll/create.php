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

print_header("Create Poll");

print_left_bar("main", "poll");
beg_main("cell");

writeln('<h1>Create Poll</h1>');

beg_form();
writeln('<input type="hidden" id="last_row" name="last_row" value="0"/>');

beg_tab("Question");
print_row(array("caption" => "Text", "text_key" => "question"));
print_row(array("caption" => "Type", "option_key" => "type_id", "option_keys" => array(1, 2, 3), "option_list" => array("Multiple Choice", "Approval Voting", "Borda Count")));
end_tab();

beg_tab("Answers", array("id" => "answers", "colspan" => 2));
writeln('	<tr>');
writeln('		<td><input id="answer_0" name="answer_0" type="text"/></td>');
writeln('		<td style="text-align: right"><a href="javascript: remove_item(0)" class="icon_minus_16">Remove</a></td>');
writeln('	</tr>');
end_tab();

writeln('<div style="margin-bottom: 8px" class="right"><a href="javascript:add_item()" class="icon_plus_16">Add</a></div>');

right_box("Publish");
?>
<script type="text/javascript">

var current = 1;

function add_item()
{
	table = document.getElementById("answers");
	row = table.insertRow(table.rows.length);
	row.id = "row_" + current;
	cell = row.insertCell(0);
	cell.innerHTML = '<input id="answer_' + current + '" name="answer_' + current + '" type="text" value=""/>';
	cell = row.insertCell(1);
	cell.style.textAlign = "right";
	cell.innerHTML = '<a href="javascript: remove_item(' + current + ')" class="icon_minus_16">Remove</a>';
	$('#last_row').val(current);

	current++;
}


function remove_item(i)
{
	table = document.getElementById("answers");
	row = document.getElementById("row_" + i);
	table.deleteRow(row.rowIndex);
}

</script>
<?

end_form();
end_main();
print_footer();
