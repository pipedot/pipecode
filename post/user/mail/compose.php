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

$to = http_post_string("to", array("len" => 250, "valid" => "[a-z][A-Z][0-9]-_.<>@+ "));
$subject = http_post_string("subject", array("len" => 250, "valid" => "[ALL]"));
$body = http_post_string("body", array("len" => 64000, "valid" => "[ALL]"));
$in_reply_to = http_post_string("in_reply_to", array("required" => false, "len" => 250, "valid" => "[a-z][A-Z][0-9]-_.@+-"));

send_web_mail($to, $subject, $body, $in_reply_to);

header("Location: /mail/");
