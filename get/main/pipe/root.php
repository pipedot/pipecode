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
include("story.php");
include("diff.php");

$pipe = item_request(TYPE_PIPE);
$pipe_code = $pipe["short_code"];
$story = db_find_rec("story", ["pipe_id" => $pipe["pipe_id"]]);

$spinner[] = ["name" => "Pipe", "link" => "/pipe/"];
$spinner[] = ["name" => $pipe["title"], "short" => $pipe_code, "link" => "/pipe/$pipe_code"];

print_header();

print_pipe($pipe["pipe_id"]);

if ($story) {
	writeln('<h2>' . get_text('History') . '</h2>');
	$row = sql("select * from story_edit where story_id = ? order by edit_time", $story["story_id"]);
	for ($i = 0; $i <= count($row); $i++) {
		if ($i == 0) {
			$old_body = $pipe["body"];
			$old_body = str_replace(' rel="nofollow"', '', $old_body);
		} else {
			$old_body = $row[$i - 1]["body"];
		}
		if ($i == count($row)) {
			$new_body = $story["body"];
			$edit_time = $story["edit_time"];
			$title = $story["title"];
			$edit_zid = $story["edit_zid"];
		} else {
			$new_body = $row[$i]["body"];
			$edit_time = $row[$i]["edit_time"];
			$title = $row[$i]["title"];
			$edit_zid = $row[$i]["edit_zid"];
		}
		$diff = diff($old_body, $new_body);

		writeln('<div class="edit-title">');
		writeln('	<div>' . date("Y-m-d H:i", $edit_time) . '</div>');
		writeln('	<div>' . $title . '</div>');
		writeln('	<div>' . $edit_zid . '</div>');
		writeln('</div>');
		writeln('<div class="edit-body">' . $diff . '</div>');
	}
}

print_comments(TYPE_PIPE, $pipe);

print_footer();
