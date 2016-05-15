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

include("$doc_root/lib/phpmailer/class.phpmailer.php");


function send_mail($email, $subject, $body)
{
	global $smtp_server;
	global $smtp_port;
	global $smtp_address;
	global $smtp_username;
	global $smtp_password;
	global $server_title;

	$mail = new PHPMailer;

	$mail->isSMTP();
	$mail->Host = $smtp_server;
	$mail->Port = $smtp_port;
	$mail->SMTPAuth = true;
	$mail->Username = $smtp_username;
	$mail->Password = $smtp_password;
	$mail->SMTPSecure = 'tls';
	$mail->XMailer = $server_title;

	$mail->From = $smtp_address;
	$mail->FromName = $server_title;
	$mail->addAddress($email);

	//$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
	//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
	//$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = $subject;
	$mail->Body    = $body;
	//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	if (!$mail->send()) {
		die("mailer error [" . $mail->ErrorInfo . "]");
	}
}


function parse_mail_address($s)
{
	$a = array();

	$beg = strpos($s, "<");
	$end = strpos($s, ">");
	if ($beg !== false && $end > $beg) {
		$email = trim(substr($s, $beg + 1, $end - $beg - 1));
		$a["name"] = trim(substr($s, 0, $beg));
	} else {
		$email = trim($s);
		$a["name"] = "";
	}

	$b = explode("@", $email);
	if (count($b) != 2) {
		$a["user"] = "";
		$a["domain"] = "";
		$a["email"] = "";
	} else {
		$a["user"] = $b[0];
		$a["domain"] = strtolower($b[1]);
		$a["email"] = $a["user"] . "@" . $a["domain"];
		if ($a["name"] == "") {
			$a["name"] = $a["email"];
		}
	}

	return $a;
}


function generate_message_id()
{
	global $server_name;

	return time() . "." . substr(crypt_sha256(rand()), 0, 8) . "@$server_name";
}


function send_web_mail($to, $subject, $body, $in_reply_to = "", $sent = true)
{
	global $auth_zid;
	global $auth_user;
	global $server_name;
	global $server_title;

	if (!$auth_user["show_name_enabled"] || $auth_user["display_name"] == "") {
		$from = "<$auth_zid>";
	} else {
		$from = $auth_user["display_name"] . " <$auth_zid>";
	}
	if (!$sent) {
		$from = "$server_title <no-reply@$server_name>";
	}

	$time = time();
	$message_id = generate_message_id();

	$header = "From: $from\r\n";
	$header .= "To: $to\r\n";
	$header .= "Date: " . date("r", $time) . "\r\n";
	$header .= "Subject: $subject\r\n";
	if ($in_reply_to != "") {
		$header .= "In-Reply-To: $in_reply_to\r\n";
	}
	$header .= "Message-ID: <$message_id>\r\n";
	$header .= "Reply-To: $from\r\n";

	$body = "$header\r\n$body";

	$a = parse_mail_address($to);
	if ($a["domain"] == $server_name) {
		if (is_local_user($a["email"])) {
			$mail = array();
			$mail["mail_id"] = 0;
			$mail["body"] = $body;
			$mail["in_reply_to"] = $in_reply_to;
			$mail["location"] = "Inbox";
			$mail["mail_from"] = $from;
			$mail["message_id"] = $message_id;
			$mail["received_time"] = $time;
			$mail["rcpt_to"] = $to;
			$mail["reply_to"] = $from;
			$mail["size"] = strlen($body);
			$mail["subject"] = $subject;
			$mail["zid"] = $a["email"];
			db_set_rec("mail", $mail);

			if ($sent) {
				$mail["location"] = "Sent";
				$mail["zid"] = $auth_zid;
				db_set_rec("mail", $mail);
			}
		}
	}
}


function print_mail_dir($location)
{
	global $auth_zid;
	global $zid;

	require_mine();

	$spinner[] = ["name" => "Mail", "link" => "/mail/"];
	$spinner[] = ["name" => $location, "link" => "/mail/$location/"];
	$actions[] = ["name" => "Compose", "icon" => "mail-compose", "link" => "/mail/compose"];

	print_header(["form" => true]);

	writeln('<table class="fill">');
	writeln('<tr>');
	writeln('<td style="vertical-align: top">');

	beg_tab();
	writeln('	<tr>');
	writeln('		<td><a href="/mail/inbox/" class="icon-16 inbox-16">Inbox</a></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td><a href="/mail/drafts/" class="icon-16 accessories-16">Drafts</a></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td><a href="/mail/junk/" class="icon-16 junk-16">Junk</a></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td><a href="/mail/outbox/" class="icon-16 send-16">Outbox</a></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td><a href="/mail/sent/" class="icon-16 sent-16">Sent</a></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	writeln('		<td><a href="/mail/trash/" class="icon-16 trash-16">Trash</a></td>');
	writeln('	</tr>');
	end_tab();

	writeln('</td>');
	writeln('<td class="fill" style="padding-left: 8px">');

	beg_tab();
	$list = db_get_list("mail", "received_time", array("zid" => $auth_zid, "location" => $location));
	$keys = array_keys($list);
	if (count($list) == 0) {
		writeln('<tr><td>(no messages)</td></tr>');
	}
	for ($i = 0; $i < count($list); $i++) {
		$message = $list[$keys[$i]];
		if ($location == "Sent") {
			$address = parse_mail_address($message["rcpt_to"]);
		} else {
			$address = parse_mail_address($message["mail_from"]);
		}
		if ($message["subject"] == "") {
			$subject = "(no subject)";
		} else {
			$subject = $message["subject"];
		}

		writeln('	<tr>');
		writeln('		<td><a href="/mail/view/' . $message["mail_id"] . '" class="icon-16 mail-16">' . $message["subject"] . '</a></td>');
		if (string_has($address["email"], "no-reply@")) {
			writeln('		<td class="center">' . $address["email"] . '</td>');
		} else {
			writeln('		<td class="center"><a href="/mail/compose?to=' . $address["email"] . '">' . $address["email"] . '</a></td>');
		}
		writeln('		<td class="right">' . date("Y-m-d H:i", $message["received_time"]) . '</td>');
		writeln('	</tr>');
	}
	end_tab();

	writeln('</td>');
	writeln('</tr>');
	writeln('</table>');

	if (count($list) > 0) {
		if ($location == "Junk" || $location == "Trash") {
			box_right("Empty");
		} else {
			box_right("Delete All");
		}
	}

	print_footer(["form" => true]);
}


function format_text_mail($body)
{
	$body = htmlentities($body);
	$body = str_replace("\r", "", $body);
	$body = str_replace("\n", "<br>", $body);
	$body = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s<])?)?)@', '<a href="$1">$1</a>', $body);

	return $body;
}


function send_notifications($comment)
{
	global $server_name;
	global $auth_zid;

	$new_subject = $comment["subject"];
	$new_comment_id = $comment["comment_id"];
	$new_comment_code = crypt_crockford_encode($new_comment_id);
	$new_zid = $comment["zid"];
	if ($new_zid == "") {
		$new_zid = "Anonymous Coward";
	}
	$parent_id = $comment["parent_id"];
	$article_id = $comment["article_id"];

	$sent_list = array();

	while ($parent_id != 0) {
		$comment = db_get_rec("comment", $parent_id);
		$parent_code = crypt_crockford_encode($comment["comment_id"]);
		$zid = $comment["zid"];
		if ($zid != "" && $zid != $auth_zid && !in_array($zid, $sent_list)) {
//			$a = article_info($comment);
//			$subject = 'Reply to "' . $comment["subject"] . '" by ' . $new_zid;
//			$body = "Your comment has a new reply.\n";
//			$body .= "\n";
//			$body .= "In the " . $a["type_id"] . ":\n";
//			$body .= htmlspecialchars_decode($a["title"]) . "\n";
//			$body .= $a["link"] . "\n";
//			$body .= "\n";
//			$body .= "Your original comment:\n";
//			$body .= $comment["subject"] . "\n";
//			$body .= "https://$server_name/comment/$parent_code\n";
//			$body .= "\n";
//			$body .= "The new reply:\n";
//			$body .= "$new_subject\n";
//			$body .= "https://$server_name/comment/$new_comment_code\n";
//			$body .= "\n";
//			send_web_mail($zid, $subject, $body, "", false);

			send_notification_comment($new_comment_id, $comment["comment_id"], $zid);
			$sent_list[] = $zid;
		}

		$parent_id = $comment["parent_id"];
	}

	$short = db_get_rec("short", $article_id);
	$article_type_id = $short["type_id"];
	if ($article_type_id == TYPE_JOURNAL) {
		$journal = db_get_rec("journal", $article_id);
		$zid = $journal["zid"];
		if (!in_array($zid, $sent_list) && $zid != $auth_zid) {
			send_notification_comment($new_comment_id, $article_id, $zid);
		}
	}
}
