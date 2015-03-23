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

function recurse($path)
{
	global $doc_root;
	global $files;

	$exclude = array("fix", "images", "log", "mini", "pub", "conf.php");
	$include = array("php", "txt", "css", "js", "html", "md", "ser");

	$a = fs_dir("$doc_root/$path");
	for ($i = 0; $i < count($a); $i++) {
		if ($path === "") {
			$file = $a[$i];
		} else {
			$file = "$path/" . $a[$i];
		}
		if (!in_array($a[$i], $exclude)) {
			$ext = fs_ext($a[$i]);
			if (in_array($ext, $include)) {
				$files[$file] = "$doc_root/$file";
				//print "adding - internal [$file] real [$doc_root/$file]\n";
			} else if (is_dir("$doc_root/$file")) {
				//print "recursing [$file]\n";
				recurse($file);
			}
		}
	}
}


$tarball = substr($server_name, 0, strpos($server_name, ".")) . "-" . gmdate("Y-m-d") . ".tar";

if (!is_file("$doc_root/www/pub/src/$tarball.gz")) {
	$files = array();
	recurse("");
	$phar = new PharData("$doc_root/www/pub/src/$tarball");
	//$phar->buildFromDirectory($doc_root, "/^(?!conf\\.)[A-Za-z0-9_-]+\\.(php|txt)/");
	//$phar->buildFromDirectory($doc_root, "/\\.(php|txt|css|js)/");
	$phar->buildFromIterator(new ArrayIterator($files));
	$phar = $phar->compress(Phar::GZ);
	unlink("$doc_root/www/pub/src/$tarball");
}

header("Location: /pub/src/$tarball.gz");

