<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
//
// This file is part of Pipecode.
//
// Pipecode is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Pipecode is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Pipecode.  If not, see <http://www.gnu.org/licenses/>.
//

include("mail.php");

if (http_post()) {
	$to = http_post_string("to", array("len" => 250, "valid" => "[a-z][A-Z][0-9]-_.<>@+ "));
	$subject = http_post_string("subject", array("len" => 250, "valid" => "[ALL]"));
	$body = http_post_string("body", array("len" => 64000, "valid" => "[ALL]"));
	$in_reply_to = http_post_string("in_reply_to", array("required" => false, "len" => 250, "valid" => "[a-z][A-Z][0-9]-_.@+-"));

	send_web_mail($to, $subject, $body, $in_reply_to);

	header("Location: /mail/");
	die();
}

$to = http_get_string("to", array("required" => false, "len" => 250, "valid" => "[a-z][A-Z][0-9]-_.<>@+ "));
$mid = http_get_int("mid", array("required" => false));

if ($mid > 0) {
	$message = db_get_rec("mail", $mid);
	$in_reply_to = $message["message_id"];
	$to = $message["mail_from"];
	$subject = $message["subject"];

	if (substr($subject, 0, 4) != "Re: ") {
		$subject = "Re: $subject";
	}
} else {
	$in_reply_to = "";
	$subject = "";
}

print_header("Mail", array("Inbox"), array("inbox"), array("/mail/"));
beg_main();
beg_form();
writeln('<input name="in_reply_to" type="hidden" value="' . $in_reply_to . '"/>');

beg_tab();
print_row(array("caption" => "To", "text_key" => "to", "text_value" => $to));
print_row(array("caption" => "Subject", "text_key" => "subject", "text_value" => $subject));
print_row(array("caption" => "Body", "textarea_key" => "body", "textarea_height" => 500));
end_tab();

right_box("Send");

end_form();
end_main();
print_footer();
