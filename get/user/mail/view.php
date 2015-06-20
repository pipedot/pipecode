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

include("mail.php");

/*
.tool_button {
	background-color: #ffffff;
	background-position: left center;
	background-repeat: no-repeat;
	border: 1px #d3d3d3 solid;
	border-radius: 4px;
	display: inline-block;
	height: 24px;
	cursor: pointer;
	line-height: 20px;
	padding: 0px;
	padding-left: 20px;
	padding-right: 2px;
	margin-right: 2px;
	vertical-align: middle;
}
.tool_button:hover {
	background-color: #aebcd4;
}
*/
//writeln('<div class="tool_button" style="background-image: url(/images/mail-compose-16.png)">Compose</div></a>');
//writeln('<div class="tool_button" style="background-image: url(/images/mail-reply-16.png)">Reply</div></a>');
//writeln('<div class="tool_button" style="background-image: url(/images/mail-forward-16.png)">Forward</div></a>');

$mail_id = http_get_int("mid");

$message = db_get_rec("mail", $mail_id);
require_mine($message["zid"]);

//$address = parse_mail_address($message["mail_from"]);

//$name = array();
//$icon = array();
//$link = array();

//if ($message["location"] != "Junk") {
//	$name[] = "Reply";
//	$icon[] = "mail-reply";
//	$link[] = "/mail/compose?mid=$mail_id";

	//$name[] = "Junk";
	//$icon[] = "junk";
	//$link[] = "/mail/mark?mid=$mail_id";
//}
//if ($message["location"] == "Trash") {
	//$name[] = "Delete";
	//$icon[] = "shred";
	//$link[] = "/mail/delete?mid=$mail_id";
//} else {
//	$name[] = "Delete";
//	$icon[] = "delete";
//	$link[] = "/mail/delete?mid=$mail_id";
//}

//$name[] = "Inbox";
//$icon[] = "inbox";
//$link[] = "/mail/";

//print_header($message["subject"], $name, $icon, $link);
if (string_has($message["mail_from"], "no-reply@")) {
	print_header($message["subject"], ["Compose"], ["mail-compose"], ["/mail/compose"], ["Mail", $message["subject"]], ["/mail/", "/mail/view?mid=$mail_id"]);
} else {
	print_header($message["subject"], ["Reply", "Compose"], ["mail-reply", "mail-compose"], ["/mail/compose?mid=$mail_id", "/mail/compose"], ["Mail", $message["subject"]], ["/mail/", "/mail/view?mid=$mail_id"]);
}
beg_main();

beg_tab();
writeln('	<tr>');
writeln('		<td style="width: 140px">From:</td>');
writeln('		<td>' . htmlentities($message["mail_from"]) . '</td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td style="width: 140px">Subject:</td>');
writeln('		<td>' . htmlentities($message["subject"]) . '</td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td style="width: 140px">To:</td>');
writeln('		<td>' . htmlentities($message["rcpt_to"]) . '</td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td style="width: 140px">Date:</td>');
writeln('		<td>' . date("Y-m-d H:i", $message["received_time"]) . '</td>');
writeln('	</tr>');
end_tab();

$body = trim(substr($message["body"], strpos($message["body"], "\r\n\r\n") + 4));
$body = format_text_mail($body);

beg_tab();
writeln('<tr>');
writeln('<td>');
writeln($body);
writeln('</td>');
writeln('</tr>');
end_tab();

beg_form();
if ($message["location"] == "Junk") {
	box_right("Restore,Delete,Expunge");
} else if ($message["location"] == "Trash") {
	box_right("Restore,Junk,Expunge");
} else if ($message["location"] == "Sent") {
	box_right("Delete");
} else {
	box_right("Junk,Delete");
}
end_form();
end_main();
print_footer();
