<?
//
// Pipecode - distributed social network
// Copyright (C) 2014-2016 Bryan Beicker <bryan@pipedot.org>
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

					db_set_rec("ban_ip", $ban_ip);
				}
			}
		} else if ($cmd == "junk") {
			$comment["junk_zid"] = $auth_zid;
			$comment["junk_time"] = $now;

			if ($value == "spam") {
				$comment["junk_status"] = 1;
				sql("delete from notification where item_id = ?", $comment["comment_id"]);
			} else if ($value == "not-junk") {
				$comment["junk_status"] = -1;
			}
			db_set_rec("comment", $comment);
		}
	}
}

header("Location: /junk/");
