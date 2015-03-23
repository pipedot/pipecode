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

include("pipe.php");

print_header("Pipe");

print_left_bar("main", "pipe");
beg_main("cell");

writeln('<h1>Stories in the Pipe</h1>');
writeln('<p>These are stories waiting to be published to the main page. Remember, anyone can <a href="/submit">submit</a> a new story!</p>');

$pipes = db_get_list("pipe", "time desc", array("closed" => 0));
if (count($pipes) == 0) {
	writeln('<h1>No stories in the pipe!</h1>');
	writeln('<p><a href="/submit">Submit</a> one now or <a href="history">view the history</a>.</p>');
}
$k = array_keys($pipes);
for ($i = 0; $i < count($pipes); $i++) {
	$pipe = $pipes[$k[$i]];

	print_pipe_small($pipe["pipe_id"], false);
}

if (count($pipes) > 0) {
	writeln('<div style="margin-top: 8px; margin-bottom: 8px; text-align: center"><a class="icon-16 calendar-16" href="history">History</a></div>');
}
end_main();

if ($auth_user["javascript_enabled"]) {
?>
<script>

function toggle_body(pipe_id)
{
	e = $('#body_' + pipe_id);
	t = $('#title_' + pipe_id);

	if (e.is(':visible')) {
		e.hide();
		t.attr("class","pipe-title-collapse");
	} else {
		e.show();
		t.attr("class","pipe-title-expand");
	}
}


function vote(pipe_id, up)
{
	data = "up=" + up;
	$.post("/pipe/" + pipe_id + "/vote", data, function(data) {
		if (data.indexOf("error:") != -1) {
			alert(data);
		} else {
			a = data.split(" ");
			pipe_id = a[0];
			score = a[1];
			result = a[2].trim();
			//alert("pipe_id [" + pipe_id + "] score [" + score + "] result [" + result + "]");

			icon_a = $('#icon_' + pipe_id + '_a');
			icon_b = $('#icon_' + pipe_id + '_b');

			if (result == "undone") {
				icon_a.attr("class", "pipe-plus");
				icon_a.attr("title", "Vote Up");
				icon_b.attr("class", "pipe-minus");
				icon_b.attr("title", "Vote Down");
			} else {
				if (result == "up") {
					icon_a.attr("class", "pipe-up");
					icon_a.attr("title", "You Voted Up");
				} else {
					icon_a.attr("class", "pipe-down");
					icon_a.attr("title", "You Voted Down");
				}
				icon_b.attr("class", "pipe-undo");
				icon_b.attr("title", "Undo Vote");
			}

			$('#score_' + pipe_id).html(score);
		}
	});
}

</script>
<?
}

print_footer();
