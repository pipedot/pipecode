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

include("mail.php");

$mail_id = http_get_int("mid");

$message = db_get_rec("mail", $mail_id);
if ($message["zid"] != $auth_zid) {
	die("not your message");
}

if (http_post("junk")) {
	$message["location"] = "Junk";
	db_set_rec("mail", $message);
	header("Location: /mail/");
	die();
}
if (http_post("delete")) {
	$message["location"] = "Trash";
	db_set_rec("mail", $message);
	header("Location: /mail/");
	die();
}
if (http_post("restore")) {
	$message["location"] = "Inbox";
	db_set_rec("mail", $message);
	header("Location: /mail/");
	die();
}
if (http_post("expunge")) {
	$message["location"] = "Trash";
	db_del_rec("mail", $message["mail_id"]);
	header("Location: /mail/trash");
	die();
}
