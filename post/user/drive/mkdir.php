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

require_login();
require_mine();

$name = http_post_string("name", "[A-Z][a-z][0-9]`~!@#\$%^&()_+-=[]{};',. ");
$parent_id = resolve_path($path, $auth_zid);

$drive_file = db_new_rec("drive_file");
$drive_file["name"] = $name;
$drive_file["parent_id"] = $parent_id;
$drive_file["type"] = DRIVE_DIR;
$drive_file["zid"] = $auth_zid;
db_set_rec("drive_file", $drive_file);

header("Location: $request_script");
