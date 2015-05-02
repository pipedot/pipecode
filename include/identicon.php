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

function identicon($s, $size)
{
	$hash = md5($s);
	$pixel = round(3 / 16 * $size);
	$pad = ($size - $pixel * 5) / 2;

	$im = imagecreatetruecolor($size, $size);
	$black = imagecolorallocate($im, 0, 0, 0);
	imagecolortransparent($im, $black);

	$red = hexdec($hash[0]) * 16;
	$green = hexdec($hash[1]) * 16;
	$blue = hexdec($hash[2]) * 16;
	$color = imagecolorallocate($im, $red, $green, $blue);

	$a = hash2array($hash);
	for ($y = 0; $y < 5; $y++) {
		for ($x = 0; $x < 5; $x++) {
			if ($a[$y][$x]) {
				imagefilledrectangle($im, $x * $pixel + $pad, $y * $pixel + $pad, ($x + 1) * $pixel + $pad - 1, ($y + 1) * $pixel + $pad - 1, $color);
			}
		}
	}

	header("Content-Type: image/png");
	imagepng($im);
	imagedestroy($im);
}


function hash2array($hash)
{
	preg_match_all('/(\w)(\w)/', $hash, $chars);
	foreach ($chars[1] as $i => $c) {
		if ($i % 3 == 0) {
			$a[$i / 3][0] = hex2bool($c);
			$a[$i / 3][4] = hex2bool($c);
		} else if ($i % 3 == 1) {
			$a[$i / 3][1] = hex2bool($c);
			$a[$i / 3][3] = hex2bool($c);
		} else {
			$a[$i / 3][2] = hex2bool($c);
		}
		ksort($a[$i / 3]);
	}

	return $a;
}


function hex2bool($hex)
{
	return hexdec($hex) % 2 == 0;
}
