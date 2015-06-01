#!/usr/bin/php5
<?php

include("lib/tools/tools.php");

if ($argc > 1) {
	$cmd = $argv[1];
} else {
	$cmd = "";
}

$distro = trim(`lsb_release -is`);
if ($distro != "Ubuntu") {
	writeln("Warning: You are not running Ubuntu, this script may not work very well.");
}

$a = posix_getpwuid(posix_getuid());
$user = $a["name"];
if ($user != "root") {
	writeln("Error: You are not root.");
	die();
}

//$doc_root = dirname(__FILE__);
//$owner = get_current_user();
//$hostname = gethostname();

//print "doc_root [$doc_root]\n";
//print "cmd [$cmd]\n";
//print "owner [$owner]\n";
//print "user [$user]\n";
//print "distro [$distro]\n";
//print "hostname [$hostname]\n";

function apache()
{
	global $distro;

	if (!fs_is_dir("/etc/apache2/sites-available")) {
		writeln("Error: unable to find apache2");
		if ($distro == "Ubuntu") {
			writeln("For the traditional prefork MPM and PHP as a module run:");
			writeln("apt-get install apache2-mpm-prefork libapache2-mod-php5");
		}
		return;
	}

	$doc_root = dirname(__FILE__);
	$a = fs_dir("/etc/apache2/sites-available");
	for ($i = 0; $i < count($a); $i++) {
		$conf = fs_slurp("/etc/apache2/sites-available/" . $a[$i]);
		if (string_has($conf, "DocumentRoot $doc_root")) {
			$site_file = $a[$i];
			writeln("Found existing site conf [/etc/apache2/sites-available/$site_file]");
			return;
		}
	}

	writeln("Creating a new site in /etc/apache2/sites-available:");
	print "Enter a conf file name [pipecode.conf]: ";
	$site_file = readln();
	if ($site_file == "") {
		$site_file = "pipecode.conf";
	}

	$hostname = gethostname();
	writeln("What is the fully qualified domain name of the site?");
	print "Enter a server name [example.com]: ";
	$server_name = readln();
	if ($server_name == "") {
		$server_name = "example.com";
	}

	writeln("FIXME: write an apache conf file with rewrite rules");
	die();
}

function nginx()
{
	global $distro;

	if (!fs_is_dir("/etc/nginx/sites-available")) {
		writeln("Error: unable to find nginx");
		if ($distro == "Ubuntu") {
			writeln("For Nginx + PHP FPM run:");
			writeln("apt-get install nginx-core php5-fpm");
		}
		return;
	}

	$doc_root = dirname(__FILE__);
	$a = fs_dir("/etc/nginx/sites-available");
	for ($i = 0; $i < count($a); $i++) {
		$conf = fs_slurp("/etc/nginx/sites-available/" . $a[$i]);
		if (string_has($conf, "root $doc_root")) {
			$site_file = $a[$i];
			writeln("Found existing site conf [/etc/nginx/sites-available/$site_file]");
			return;
		}
	}

	writeln("Creating a new site in /etc/nginx/sites-available:");
	print "Enter a conf file name [pipecode]: ";
	$site_file = readln();
	if ($site_file == "") {
		$site_file = "pipecode";
	}

	$hostname = gethostname();
	writeln("What is the fully qualified domain name of the site?");
	print "Enter a server name [example.com]: ";
	$server_name = readln();
	if ($server_name == "") {
		$server_name = "example.com";
	}

	$conf = "server {
	listen 80;

	root $doc_root;
	index index.php index.html;
	autoindex on;
	client_max_body_size 50M;

	server_name $server_name;

	location / {
		try_files \$uri \$uri/ /index.php;
	}

	location ~ \.php$ {
		fastcgi_split_path_info ^(.+\.php)(/.+)$;
		fastcgi_pass unix:/var/run/php5-fpm.sock;
		fastcgi_index index.php;
		fastcgi_read_timeout 900;
		include fastcgi_params;
	}

	location ~* \.(ico|css|js)$ {
		expires 90d;
	}

	location ~* /images/.*\.(png|jpg)$ {
		expires 90d;
	}

	location ~* /pub/.*\.(png|jpg)$ {
		expires 90d;
	}

	error_log /var/log/error.log error;
	access_log /var/log/access.log;
}
";
	if (!fs_slap("/etc/nginx/sites-available/$site_file", $conf)) {
		writeln("Error: unable to write conf file");
		return;
	}
	if (!symlink("../sites-available/$site_file", "/etc/nginx/sites-enabled/$site_file")) {
		writeln("Error: unable to symlink conf file");
	}
}

switch ($cmd) {
	case "apache":
		apache();
		break;
	case "nginx":
		nginx();
		break;
	default:
		writeln("unknown command [$cmd]");
		die();
}
