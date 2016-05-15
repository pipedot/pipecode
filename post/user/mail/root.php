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

include("mail.php");

require_mine();

$location = ucwords($s2);

$locations = ["Inbox", "Drafts", "Junk", "Outbox", "Sent", "Trash"];
if (!in_array($location, $locations)) {
	fatal("Directory not found");
}

if (http_post("delete_all")) {
	sql("update mail set location = 'Trash' where zid = ? and location = ?", $auth_zid, $location);
} else if (http_post("empty")) {
	sql("delete from mail where zid = ? and location = ?", $auth_zid, $location);
}

header("Location: /mail/$s2/");

