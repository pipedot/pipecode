<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
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

$pipe_id = $s2;
if (!string_uses($pipe_id, "[a-z][0-9]_")) {
	die("invalid pipe_id [$pipe_id]");
}
if (!db_has_rec("pipe", $pipe_id)) {
	die("error: pipe not found [$pipe_id]");
}

if (array_key_exists("up_x", $_POST) || array_key_exists("down_x", $_POST) || array_key_exists("undo_x", $_POST)) {
	$redirect = true;
	$up = array_key_exists("up_x", $_POST);
} else {
	$redirect = false;
	$up = http_post_int("up");
}

if (db_has_rec("pipe_vote", array("pipe_id" => $pipe_id, "zid" => $auth_zid))) {
	db_del_rec("pipe_vote", array("pipe_id" => $pipe_id, "zid" => $auth_zid));
	$result = "undone";
} else {
	if ($up) {
		$result = "up";
	} else {
		$result = "down";
	}

	$pipe_vote = array();
	$pipe_vote["pipe_id"] = $pipe_id;
	$pipe_vote["zid"] = $auth_zid;
	$pipe_vote["time"] = time();
	if ($up) {
		$pipe_vote["value"] = 1;
	} else {
		$pipe_vote["value"] = -1;
	}
	db_set_rec("pipe_vote", $pipe_vote);
}

if ($redirect) {
	header("Location: /pipe/");
	die();
}

$row = sql("select sum(value) as score from pipe_vote where pipe_id = ?", $pipe_id);
$score = (int) $row[0]["score"];
if ($score > 0) {
	$score = "+$score";
}

writeln("$pipe_id $score $result");
