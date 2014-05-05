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

$pid = (int) $s2;
if (!string_uses($pid, "[0-9]")) {
	die("error: invalid pid [$pid]");
}

if (!http_post()) {
	die("error: post method required");
}

if (!db_has_rec("pipe", $pid)) {
	die("error: pipe not found [$pid]");
}


//var_dump($_POST);
//if (!empty(@$_POST["up_x"]) || !empty(@$_POST["down_x"])) {
if (array_key_exists("up_x", $_POST) || array_key_exists("down_x", $_POST) || array_key_exists("undo_x", $_POST)) {
	$redirect = true;
	$up = array_key_exists("up_x", $_POST);
	//die("up");
} else {
	$redirect = false;
	$up = http_post_int("up");
}
//die("here");

if (db_has_rec("pipe_vote", array("pid" => $pid, "zid" => $auth_zid))) {
	//$pipe_vote = db_get_rec("pipe_vote", array("pid" => $pid, "zid" => $auth_zid));
	//$value = $pipe_vote["value"];
	db_del_rec("pipe_vote", array("pid" => $pid, "zid" => $auth_zid));
	$result = "undone";
} else {
	if ($up) {
		$result = "up";
	} else {
		$result = "down";
	}

	$pipe_vote = array();
	$pipe_vote["pid"] = $pid;
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

$row = run_sql("select sum(value) as score from pipe_vote where pid = ?", array($pid));
$score = (int) $row[0]["score"];
if ($score > 0) {
	$score = "+$score";
}

writeln("$pid $score $result");
