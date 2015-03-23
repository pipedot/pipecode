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

if (!$auth_user["admin"] && !$auth_user["editor"]) {
	die("not an editor or admin");
}

header_text();

$keys = array_keys($_POST);
for ($i = 0; $i < count($keys); $i++) {
	$name = $keys[$i];
	$value = $_POST[$name];
	$a = explode("_", $name);
	if (count($a) == 2) {
		$cmd = $a[0];
		$comment_code = $a[1];
		$comment_id = crypt_crockford_decode($comment_code);
		$comment = db_get_rec("comment", $comment_id);

		if ($cmd == "ban") {
			if ($comment["remote_ip"] != "") {
				if (!db_has_rec("ban_ip", $comment["remote_ip"])) {
					$ban_ip = db_new_rec("ban_ip");
					$ban_ip["remote_ip"] = $comment["remote_ip"];
					$ban_ip["short_id"] = $comment_id;
					$ban_ip["zid"] = $auth_zid;

					print "banning [$comment_code] [{$comment["remote_ip"]}]\n";
					db_set_rec("ban_ip", $ban_ip);
				}
			}
		} else if ($cmd == "junk") {
			$comment["junk_zid"] = $auth_zid;
			$comment["junk_time"] = $now;

			if ($value == "spam") {
				print "spam [$comment_code]\n";
				$comment["junk_status"] = 1;
			} else if ($value == "not-junk") {
				print "not junk [$short_code]\n";
				$comment["junk_status"] = -1;
			}
			db_set_rec("comment", $comment);
		}
	}
}

