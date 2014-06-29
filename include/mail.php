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

	if ($auth_user["real_name"] == "") {
		$from = "<$auth_zid>";
	} else {
		$from = $auth_user["real_name"] . " <$auth_zid>";
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

	print_header($location, array("Compose"), array("mail_compose"), array("/mail/compose"));

	beg_main();
	writeln('<table class="fill">');
	writeln('<tr>');
	writeln('<td style="vertical-align: top">');

	beg_tab();
	writeln('	<tr>');
	//writeln('		<td><a href="/mail/" class="icon_16" style="background-image: url(/images/inbox-16.png)">Inbox</a></td>');
	writeln('		<td><a href="/mail/" class="icon_inbox_16">Inbox</a></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	//writeln('		<td><a href="/mail/sent" class="icon_16" style="background-image: url(/images/sent-16.png)">Sent</a></td>');
	writeln('		<td><a href="/mail/sent" class="icon_sent_16">Sent</a></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	//writeln('		<td><a href="/mail/junk" class="icon_16" style="background-image: url(/images/junk-16.png)">Junk</a></td>');
	writeln('		<td><a href="/mail/junk" class="icon_junk_16">Junk</a></td>');
	writeln('	</tr>');
	writeln('	<tr>');
	//writeln('		<td><a href="/mail/trash" class="icon_16" style="background-image: url(/images/trash-16.png)">Trash</a></td>');
	writeln('		<td><a href="/mail/trash" class="icon_trash_16">Trash</a></td>');
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
		writeln('		<td><a href="view?mid=' . $message["mail_id"] . '" class="icon_16" style="background-image: url(/images/mail-16.png)">' . $message["subject"] . '</a></td>');
		if (string_has($address["email"], "no-reply@")) {
			writeln('		<td class="center">' . $address["email"] . '</td>');
		} else {
			writeln('		<td class="center"><a href="compose?to=' . $address["email"] . '">' . $address["email"] . '</a></td>');
		}
		writeln('		<td class="right">' . date("Y-m-d H:i", $message["received_time"]) . '</td>');
		writeln('	</tr>');
	}
	end_tab();

	writeln('</td>');
	writeln('</tr>');
	writeln('</table>');

	if (count($list) > 0) {
		beg_form();
		if ($location == "Junk" || $location == "Trash") {
			right_box("Empty");
		} else {
			right_box("Delete All");
		}
		end_form();
	}

	end_main();
	print_footer();
}


function format_text_mail($body)
{
	$body = htmlentities($body);
	$body = str_replace("\r", "", $body);
	$body = str_replace("\n", "<br/>", $body);
	$body = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s<])?)?)@', '<a href="$1">$1</a>', $body);

	return $body;
}


function send_notifications($parent, $comment)
{
	global $server_name;
	global $auth_zid;

	$new_subject = $comment["subject"];
	$new_cid = $comment["cid"];
	$new_zid = $comment["zid"];
	if ($new_zid == "") {
		$new_zid = "Anonymous Coward";
	}
	$parent = $comment["parent"];
	$sent_list = array();

	while ($parent > 0) {
		$comment = db_get_rec("comment", $parent);
		$zid = $comment["zid"];
		if ($zid != "" && $zid != $auth_zid && !in_array($zid, $sent_list)) {
			$a = article_info($comment);
			$subject = 'Reply to "' . $comment["subject"] . '" by ' . $new_zid;
			$body = "Your comment has a new reply.\n";
			$body .= "\n";
			$body .= "In the " . $a["type"] . ":\n";
			$body .= htmlspecialchars_decode($a["title"]) . "\n";
			$body .= $a["link"] . "\n";
			$body .= "\n";
			$body .= "Your original comment:\n";
			$body .= $comment["subject"] . "\n";
			$body .= "https://$server_name/comment/$parent\n";
			$body .= "\n";
			$body .= "The new reply:\n";
			$body .= "$new_subject\n";
			$body .= "https://$server_name/comment/$new_cid\n";
			$body .= "\n";

			send_web_mail($zid, $subject, $body, "", false);
			$sent_list[] = $zid;
		}

		$parent = $comment["parent"];
	}
}
