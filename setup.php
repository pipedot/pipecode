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

if (is_file("$doc_root/conf.php")) {
	die("already setup");
}

if (http_post()) {
	$server_name = http_post_string("server_name", array("len" => 50, "valid" => "[a-z][0-9]-."));
	$server_title = http_post_string("server_title", array("len" => 50, "valid" => "[a-z][A-Z][0-9]- "));
	$server_slogan = http_post_string("server_slogan", array("len" => 50));
	$smtp_server = http_post_string("smtp_server", array("len" => 50, "valid" => "[a-z][0-9]-."));
	$smtp_port = http_post_string("smtp_port", array("len" => 50, "valid" => "[0-9]"));
	$smtp_address = http_post_string("smtp_address", array("len" => 50, "valid" => "[a-z][0-9]-.@"));
	$smtp_username = http_post_string("smtp_username", array("len" => 50, "valid" => "[a-z][0-9]-.@"));
	$smtp_password = http_post_string("smtp_password", array("len" => 100));
	$sql_server = http_post_string("sql_server", array("len" => 50, "valid" => "[a-z][0-9]-."));
	$sql_user = http_post_string("sql_user", array("len" => 50, "valid" => "[a-z][0-9]"));
	$sql_pass = http_post_string("sql_pass", array("len" => 100));
	$sql_database = http_post_string("sql_database", array("len" => 50, "valid" => "[a-z][0-9]"));
	$captcha_key = http_post_string("captcha_key", array("len" => 32, "required" => false, "valid" => "[a-z][0-9]"));
	$admin_username = http_post_string("admin_username", array("len" => 50, "valid" => "[a-z][0-9]"));
	$admin_password = http_post_string("admin_password", array("len" => 100));

	$s = "<?\n";
	$s .= "\n";
	$s .= "\$server_name = \"$server_name\";\n";
	$s .= "\$server_title = \"$server_title\";\n";
	$s .= "\$server_slogan = \"$server_slogan\";\n";
	$s .= "\n";
	$s .= "\$smtp_server = \"$smtp_server\";\n";
	$s .= "\$smtp_port = \"$smtp_port\";\n";
	$s .= "\$smtp_address = \"$smtp_address\";\n";
	$s .= "\$smtp_username = \"$smtp_username\";\n";
	$s .= "\$smtp_password = \"$smtp_password\";\n";
	$s .= "\n";
	$s .= "\$sql_server = \"mysql:host=$sql_server;dbname=$sql_database\";\n";
	$s .= "\$sql_user = \"$sql_user\";\n";
	$s .= "\$sql_pass = \"$sql_pass\";\n";
	$s .= "\n";
	$s .= "\$cache_enabled = false;\n";
	$s .= "\$apc_enabled = false;\n";
	$s .= "\$memcache_server = \"\";\n";
	$s .= "\n";
	$s .= "\$auth_key = \"" . random_hash() . "\";\n";
	$s .= "\$auth_expire = 86400 * 365;\n";
	$s .= "\n";
	$s .= "\$captcha_key = \"$captcha_key\";\n";
	$s .= "\n";
	$s .= "date_default_timezone_set(\"UTC\");\n";
	$s .= "\$https_enabled = true;\n";
	$s .= "\$story_image_enabled = false;\n";

	$sql_server = "mysql:host=$sql_server";
	$sql_open = false;
	open_database();

	fs_slap("$doc_root/conf.php", $s);

	if (!db_has_database($sql_database)) {
		sql("create database $sql_database");
		sql("use $sql_database");
		run_sql_file("$doc_root/schema.sql");
		run_sql_file("$doc_root/default.sql");

		$zid = "$admin_username@$server_name";
		$salt = random_hash();
		$pass = crypt_sha256("$admin_password$salt");
		sql("insert into user_conf (zid, name, value) values (?, ?, ?)", $zid, "admin", "1");
		sql("insert into user_conf (zid, name, value) values (?, ?, ?)", $zid, "editor", "1");
		sql("insert into user_conf (zid, name, value) values (?, ?, ?)", $zid, "password", $pass);
		sql("insert into user_conf (zid, name, value) values (?, ?, ?)", $zid, "salt", $salt);
	}

	header("Location: /");
	die();
}

writeln('<!DOCTYPE html>');
writeln('<html>');
writeln('<head>');
writeln('<title>Pipecode Setup</title>');
writeln('<meta http-equiv="Content-type" content="text/html;charset=UTF-8">');
writeln('<link rel="stylesheet" href="/style.css" type="text/css"/>');
writeln('</head>');
writeln('<body>');
writeln('<img alt="Pipecode" src="/images/logo-top.png" style="margin-bottom: 8px"/>');

beg_form();

beg_tab();
print_row(array("caption" => "Server Name", "text_key" => "server_name", "text_value" => "example.com"));
print_row(array("caption" => "Server Title", "text_key" => "server_title", "text_value" => "Example Site"));
print_row(array("caption" => "Server Slogan", "text_key" => "server_slogan", "text_value" => "Nerd News Network"));
end_tab();

beg_tab();
print_row(array("caption" => "SMTP Server", "text_key" => "smtp_server", "text_value" => "mail.example.com"));
print_row(array("caption" => "SMTP Port", "text_key" => "smtp_port", "text_value" => "587"));
print_row(array("caption" => "SMTP Address", "text_key" => "smtp_address", "text_value" => "mailuser@example.com"));
print_row(array("caption" => "SMTP Username", "text_key" => "smtp_username", "text_value" => "mailuser"));
print_row(array("caption" => "SMTP Password", "text_key" => "smtp_password", "text_value" => "password"));
end_tab();

beg_tab();
print_row(array("caption" => "SQL Server", "text_key" => "sql_server", "text_value" => "127.0.0.1"));
print_row(array("caption" => "SQL Username", "text_key" => "sql_user", "text_value" => "sql"));
print_row(array("caption" => "SQL Password", "text_key" => "sql_pass", "text_value" => "password"));
print_row(array("caption" => "SQL Database", "text_key" => "sql_database", "text_value" => "pipecode"));
end_tab();

beg_tab();
print_row(array("caption" => "CAPTCHA API Key", "text_key" => "captcha_key", "text_value" => ""));
end_tab();

beg_tab();
print_row(array("caption" => "Admin Username", "text_key" => "admin_username", "text_value" => "admin"));
print_row(array("caption" => "Admin Password", "text_key" => "admin_password", "text_value" => "crunchyfrog"));
end_tab();

right_box("Save");

end_form();

print_footer();
