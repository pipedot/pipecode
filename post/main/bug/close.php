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

if (!$auth_user["admin"] || !$auth_user["editor"]) {
	die("you are not an editor");
}

$bug = item_request(TYPE_BUG);
$bug["closed"] = 1;
$bug["closed_zid"] = $auth_zid;
db_set_rec("bug", $bug);

header("Location: /bug/{$bug["short_code"]}");
