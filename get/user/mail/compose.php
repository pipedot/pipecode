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

$to = http_get_string("to", array("required" => false, "len" => 250, "valid" => "[a-z][A-Z][0-9]-_.<>@+ "));
$cid = http_get_int("cid", array("required" => false));
$mid = http_get_int("mid", array("required" => false));

$to = "";
$in_reply_to = "";
$subject = "";
if ($mid > 0) {
	$message = db_get_rec("mail", $mid);
	$in_reply_to = $message["message_id"];
	$to = $message["mail_from"];
	$subject = $message["subject"];

	if (substr($subject, 0, 4) != "Re: ") {
		$subject = "Re: $subject";
	}
} else if ($cid > 0) {
	$contact = db_get_rec("contact", $cid);
	$to = $contact["email"];
}

print_header("Compose", [], [], [], ["Mail", "Compose"], ["/mail/", "/mail/compose"]);
beg_main();
beg_form();
writeln('<input name="in_reply_to" type="hidden" value="' . $in_reply_to . '">');

beg_tab();
print_row(array("caption" => "To", "text_key" => "to", "text_value" => $to));
print_row(array("caption" => "Subject", "text_key" => "subject", "text_value" => $subject));
print_row(array("caption" => "Body", "textarea_key" => "body", "textarea_height" => 500));
end_tab();

box_right("Send");

end_form();
end_main();
print_footer();
