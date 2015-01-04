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

include("drive.php");

if (!$auth_user["editor"] && !$auth_user["admin"]) {
	die("not an editor or an admin");
}

$bug_file = find_rec("bug_file");

if ($bug_file["type"] == "jpg" || $bug_file["type"] == "png") {
	$path = $doc_root . "/www" . public_path($bug_file["time"]);

	fs_remove("$path/bug_file_{$bug_file["short_code"]}_128x128.jpg");
	fs_remove("$path/bug_file_{$bug_file["short_code"]}_256x256.jpg");
}
fs_remove("$doc_root/www/pub/bug/{$bug_file["short_code"]}." . $bug_file["type"]);

db_del_rec("bug_file", $short_id);

header("Location: /bug/{$bug_file["short_code"]}");
