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

include("pipe.php");

print_header("Pipe");

writeln('<table class="fill">');
writeln('<tr>');
writeln('<td class="left_col">');
print_left_bar("main", "pipe");
writeln('</td>');
writeln('<td class="fill">');

writeln('<h1>Stories in the Pipe</h1>');
writeln('<p>These are stories waiting to be published to the main page. Remember, anyone can <a href="/submit">submit</a> a new story!</p>');

$pipes = db_get_list("pipe", "time desc", array("closed" => 0));
if (count($pipes) == 0) {
	writeln('<h1>No stories in the pipe!</h1>');
	writeln('<p><a href="/submit">Submit</a> one now or <a href="/pipe/history">view the history</a>.</p>');
}
$k = array_keys($pipes);
for ($i = 0; $i < count($pipes); $i++) {
	$pipe = $pipes[$k[$i]];

	print_pipe_small($pipe["pid"], false);
}

if (count($pipes) > 0) {
	writeln('<div style="margin-top: 8px; text-align: center"><a href="/pipe/history">History</a></div>');
}
writeln('</td>');
writeln('</tr>');
writeln('</table>');

if ($auth_user["javascript_enabled"]) {
?>
<script>

function toggle_body(pid)
{
	e = $('#body_' + pid);
	t = $('#title_' + pid);

	if (e.is(':visible')) {
		e.hide();
		t.attr("class","pipe_title_collapse");
	} else {
		e.show();
		t.attr("class","pipe_title_expand");
	}
}


function vote(pid, up)
{
	data = "up=" + up;
	$.post("/pipe/" + pid + "/vote", data, function(data) {
		if (data.indexOf("error:") != -1) {
			alert(data);
		} else {
			a = data.split(" ");
			pid = a[0];
			score = a[1];
			result = a[2].trim();
			//alert("pid [" + pid + "] score [" + score + "] result [" + result + "]");

			icon_a = $('#icon_' + pid + '_a');
			icon_b = $('#icon_' + pid + '_b');

			if (result == "undone") {
				icon_a.attr("src", "/images/add-32.png");
				icon_a.attr("alt", "Vote Up");
				icon_a.attr("title", "Vote Up");
				icon_b.attr("src", "/images/remove-32.png");
				icon_b.attr("alt", "Vote Down");
				icon_b.attr("title", "Vote Down");
			} else {
				if (result == "up") {
					icon_a.attr("src", "/images/up-32.png");
					icon_a.attr("alt", "You Voted Up");
					icon_a.attr("title", "You Voted Up");
				} else {
					icon_a.attr("src", "/images/down-32.png");
					icon_a.attr("alt", "You Voted Down");
					icon_a.attr("title", "You Voted Down");
				}
				icon_b.attr("src", "/images/undo-32.png");
				icon_b.attr("alt", "Undo Vote");
				icon_b.attr("title", "Undo Vote");
			}

			$('#score_' + pid).html(score);
		}
	});
}

</script>
<?
}

print_footer();
