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


function create_avatar($zid, $data_64, $data_128, $data_256)
{
	$hash_64 = crypt_sha256($data_64);
	$hash_128 = crypt_sha256($data_128);
	$hash_256 = crypt_sha256($data_256);

	$avatar_id = create_short(TYPE_AVATAR);
	$avatar = db_new_rec("avatar");
	$avatar["avatar_id"] = $avatar_id;
	$avatar["hash_64"] = $hash_64;
	$avatar["hash_128"] = $hash_128;
	$avatar["hash_256"] = $hash_256;
	$avatar["zid"] = $zid;
	db_set_rec("avatar", $avatar);

	drive_set($data_64);
	drive_set($data_128);
	drive_set($data_256);

	drive_link($hash_64, $avatar["avatar_id"], TYPE_AVATAR, $zid);
	drive_link($hash_128, $avatar["avatar_id"], TYPE_AVATAR, $zid);
	drive_link($hash_256, $avatar["avatar_id"], TYPE_AVATAR, $zid);

	return $avatar_id;
}


function create_image_avatar($zid, $src_img)
{
	global $doc_root;

	$original_width = imagesx($src_img);
	$original_height = imagesy($src_img);
	if ($original_width < 256 || $original_height < 256) {
		die("avatar must be at least 256 x 256");
	}

	list($user, $host) = explode("@", $zid);
	$sizes = [64, 128, 256];
	$data = [];
	for ($i = 0; $i < count($sizes); $i++) {
		if ($sizes[$i] == 64) {
			$tmp_img = resize_image($src_img, $sizes[$i], $sizes[$i], true);
			$file = "$doc_root/tmp/$user-$sizes[$i].png";
			imagepng($tmp_img, $file);
		} else {
			$tmp_img = resize_image($src_img, $sizes[$i], $sizes[$i], false);
			$file = "$doc_root/tmp/$user-$sizes[$i].jpg";
			imagejpeg($tmp_img, $file);
		}
		imagedestroy($tmp_img);
		$data[$i] = fs_slurp($file);
		fs_unlink($file);
	}

	return create_avatar($zid, $data[0], $data[1], $data[2]);
}


function create_identicon_avatar($zid)
{
	$data_64 = identicon($zid, 64);
	$data_128 = identicon($zid, 128);
	$data_256 = identicon($zid, 256);

	return create_avatar($zid, $data_64, $data_128, $data_256);
}


function create_gravatar_avatar($zid)
{
	global $doc_root;

	$a = explode("@", $zid);
	if (count($a) != 2) {
		return false;
	}
	$user = $a[0];
	$server = $a[1];
	if (!string_uses($user, "[a-z][0-9]") || !string_uses($server, "[a-z][0-9].-") || string_has($server, "..")) {
		return false;
	}
	$user_conf = db_get_conf("user_conf", $zid);

	$email = strtolower(trim($user_conf["email"]));
	$url = "http://www.gravatar.com/avatar/" . md5($email) . "?s=256&d=404&r=pg";
	$src_data = http_slurp($url);
	$src_img = @imagecreatefromstring($src_data);
	$original_width = @imagesx($src_img);
	$original_height = @imagesy($src_img);
	if ($original_width < 256 || $original_height < 256) {
		return false;
	}

	$sizes = [64, 128];
	$data = [];
	for ($i = 0; $i < count($sizes); $i++) {
		if ($sizes[$i] == 64) {
			$tmp_img = resize_image($src_img, $sizes[$i], $sizes[$i], true);
			$file = "$doc_root/tmp/$user-$sizes[$i].png";
			imagepng($tmp_img, $file);
		} else {
			$tmp_img = resize_image($src_img, $sizes[$i], $sizes[$i], false);
			$file = "$doc_root/tmp/$user-$sizes[$i].jpg";
			imagejpeg($tmp_img, $file);
		}
		imagedestroy($tmp_img);
		$data[$i] = fs_slurp($file);
		fs_unlink($file);
	}

	return create_avatar($zid, $data[0], $data[1], $src_data);
}


function identicon($zid, $size)
{
	global $doc_root;

	if ($size != 64 && $size != 128 && $size != 256) {
		die("invalid size");
	}

	$hash = md5($zid);
	$pixel = round(3 / 16 * $size);
	$pad = ($size - $pixel * 5) / 2;

	$im = imagecreatetruecolor($size, $size);
	if ($size == 64) {
		$black = imagecolorallocate($im, 0, 0, 0);
		imagecolortransparent($im, $black);
	} else {
		$white = imagecolorallocate($im, 255, 255, 255);
		imagefilledrectangle($im, 0, 0, $size, $size, $white);
	}

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

	list($user, $domain) = explode("@", $zid);
	if ($size == 64) {
		$file = "$doc_root/tmp/$user-$size.png";
		imagepng($im, $file);
	} else {
		$file = "$doc_root/tmp/$user-$size.jpg";
		imagejpeg($im, $file);
	}
	imagedestroy($im);
	$data = fs_slurp($file);
	fs_unlink($file);

	return $data;
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
