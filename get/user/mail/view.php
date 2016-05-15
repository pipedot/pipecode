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

$mail_id = $s3;
if (!string_uses($mail_id, "[0-9]")) {
	fatal("Invalid message");
}

$message = db_get_rec("mail", $mail_id);
require_mine($message["zid"]);

$spinner[] = ["name" => "Mail", "link" => "/mail/"];
$spinner[] = ["name" => $message["subject"], "short" => $mail_id, "link" => "/mail/view/$mail_id"];
$actions[] = ["name" => "Compose", "icon" => "mail-compose", "link" => "/mail/compose"];
if (!string_has($message["mail_from"], "no-reply@")) {
	$actions[] = ["name" => "Reply", "icon" => "mail-reply", "link" => "/mail/compose?mid=$mail_id"];
}

print_header(["form" => true]);

beg_tab();
writeln('	<tr>');
writeln('		<td style="width: 140px">' . get_text('From:') . '</td>');
writeln('		<td>' . htmlentities($message["mail_from"]) . '</td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td style="width: 140px">' . get_text('Subject:') . '</td>');
writeln('		<td>' . htmlentities($message["subject"]) . '</td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td style="width: 140px">' . get_text('To:') . '</td>');
writeln('		<td>' . htmlentities($message["rcpt_to"]) . '</td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td style="width: 140px">' . get_text('Date:') . '</td>');
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

if ($message["location"] == "Junk") {
	box_right("Restore,Delete,Expunge");
} else if ($message["location"] == "Trash") {
	box_right("Restore,Junk,Expunge");
} else if ($message["location"] == "Sent") {
	box_right("Delete");
} else {
	box_right("Junk,Delete");
}

print_footer(["form" => true]);
