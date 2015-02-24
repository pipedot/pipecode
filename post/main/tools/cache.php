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

if (!$auth_user["admin"]) {
	die("not an admin");
}

$hash = http_post_string("hash", array("valid" => "[a-z][A-Z][0-9]~#%&()-_+=[];:./?", "len" => 200));

if (!string_uses($hash, "[0-9]abcdef")) {
	$hash = crypt_sha256($hash);
}
$cache = db_find_rec("cache", $hash);

if ($cache === false) {
	die("cache item not found");
}

//$row = sql("select count(*) as links from drive_data

db_del_rec("cache", $hash);

die("deleted item [$hash]");

