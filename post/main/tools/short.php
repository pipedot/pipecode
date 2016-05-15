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

if (http_post("encode")) {
	$short_encode = http_post_int("short_encode");
	die("Short Encode [$short_encode] = [" . crypt_crockford_encode($short_encode) . "]");
}
if (http_post("decode")) {
	$short_decode = http_post_string("short_decode", array("len" => 50));
	$short_decode = string_clean($short_decode, "[A-Z][a-z][0-9]");
	die("Short Decode [$short_decode] = [" . crypt_crockford_decode($short_decode) . "]");
}

