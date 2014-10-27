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

include("clean.php");

if (!$auth_user["admin"]) {
	die("not an admin");
}

$old_slug = http_get_string("slug", array("len" => 100, "valid" => "[a-z][A-Z][0-9]-_."));
$title = clean_subject();
$new_slug = clean_slug();
list($clean, $dirty) = clean_body();

if ($old_slug != $new_slug) {
	db_del_rec("page", $old_slug);
}

$page = array();
$page["slug"] = $new_slug;
$page["title"] = $title;
$page["body"] = $clean;
db_set_rec("page", $page);

header("Location: ./");
