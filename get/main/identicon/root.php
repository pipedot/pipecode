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

include("identicon.php");

//print "s2 [$s2]";
if (substr($s2, -4) != ".png") {
	die("filename must end in .png");
}
$s = substr($s2, 0, -4);

$pos = strrpos($s, "-");
if ($pos === false) {
	die("filename must include size");
}
$size = substr($s, $pos + 1);
$valid = array("32", "64", "128", "256");
if (!in_array($size, $valid)) {
	die("invalid size");
}
$s = substr($s, 0, $pos);
//print "size [$size]";

if (!string_uses($s, "[a-z][0-9]-.")) {
	die("invalid name");
}

//$time = fs_time(__FILE__);
//$etag = md5($s);
//if (!http_modified($time, $etag)) {
//	http_response_code(304);
//} else {
//	header("Last-Modified: " . gmdate("D, j M Y H:i:s", $time) . " GMT");
	//header("ETag: \"$etag\"");
	header("Expires: " . gmdate("D, d M Y H:i:s \G\M\T", time() + 1 * YEARS));
	identicon($s, $size);
	//print "hi";
//}
