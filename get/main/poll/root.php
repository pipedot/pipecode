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

include("poll.php");

if (string_has($s2, "-") && $s3 === "") {
	$date = $s2;
	$date_beg = strtotime("$date GMT");
	if ($date_beg === false) {
		fatal("Invalid date");
	}
	$date_end = $date_beg + DAYS;
	$poll_date = gmdate("Y-m-d", $date_beg);

	$spinner[] = ["name" => "Poll", "link" => "/poll/"];
	$spinner[] = ["name" => $poll_date, "link" => "/poll/$poll_date/"];

	print_header();

	dict_beg();
	$row = sql("select question, slug, publish_time from poll where publish_time > ? and publish_time < ? order by publish_time desc", $date_beg, $date_end);
	if (count($row) == 0) {
		dict_none();
	}
	for ($i = 0; $i < count($row); $i++) {
		dict_row('<a href="/poll/' . $poll_date . "/" . $row[$i]["slug"] . '">' . $row[$i]["question"] . '</a>', $poll_date);
	}
	dict_end();

	print_footer();
} else {
	$poll = item_request(TYPE_POLL);
	$poll_code = $poll["short_code"];
	$poll_date = gmdate("Y-m-d", $poll["publish_time"]);
	//$clean = clean_url($poll["question"]);
	$type_id = $poll["type_id"];

	$spinner[] = ["name" => "Poll", "link" => "/poll/"];
	if ($s2 == $poll_date) {
		$spinner[] = ["name" => $poll_date, "link" => "/poll/$poll_date/"];
		$spinner[] = ["name" => $poll["question"], "link" => "/poll/$poll_date/" . $poll["slug"]];
		print_header(["title" => "Poll"]);
	} else {
		$spinner[] = ["name" => $poll["question"], "link" => "/poll/$poll_code"];
		print_header(["title" => "Poll"]);
	}

	vote_box($poll["poll_id"], false);
	print_comments(TYPE_POLL, $poll);

	print_footer();
}
