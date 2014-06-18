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

function build_preview_images($body)
{
	$img_ids = array();
	$doc_urls = list_documents($body);

	for ($d = 0; $d < count($doc_urls); $d++) {
		$img_urls = download_document($doc_urls[$d]);

		for ($i = 0; $i < count($img_urls); $i++) {
			$id = download_image($doc_urls[$d], $img_urls[$i]);
			if ($id != 0) {
				$img_ids[] = $id;
			}
		}
	}

	return $img_ids;
}


function list_documents($data)
{
	$list = array();
	$beg = strpos($data, "<a ");
	while ($beg !== false) {
		$end = strpos($data, ">", $beg);
		if ($end === false) {
			break;
		}
		$tag = substr($data, $beg + 3, $end - $beg - 3);
		$map = map_from_tag_string($tag);
		if (array_key_exists("href", $map)) {
			$href = $map["href"];
			if (substr($href, 0, 4) == "http") {
				//print "doc_url [$href]\n";
				$list[] = $href;
			}
		}
		$beg = strpos($data, "<a ", $end + 1);
	}

	return $list;
}


function download_document($url)
{
	$ext = fs_ext($url);
	if ($ext == "jpg" || $ext == "png") {
		return array($url);
	}

	$images = array();
	$data = http_slurp($url);
	//print "data [$data]\n";
	$u = parse_url($url);
	$base_url = $u["scheme"] . "://" . $u["host"];
	if (array_key_exists("port", $u)) {
		$base_url .= ":" . $u["port"];
	}
	if (array_key_exists("path", $u)) {
		if (substr($u["path"], -1) == "/") {
			$base_dir = $u["path"];
		} else {
			$base_dir = dirname($u["path"]);
		}
	} else {
		$base_dir = "/";
	}
	//print "base_url [$base_url]\n";
	//print "base_dir [$base_dir]\n";
	//var_dump($u);

	$beg = stripos($data, "<img ");
	while ($beg !== false) {
		$end = strpos($data, ">", $beg);
		if ($end === false) {
			break;
		}
		$tag = substr($data, $beg + 5, $end - $beg - 5);
		//print "tag [$tag]\n";
		//$tag = str_replace(" ", "", $tag);
		$map = map_from_tag_string($tag);
		if (array_key_exists("src", $map)) {
			$src = $map["src"];
			//print "src [$src]\n";

			//$src = "../../images/logo-256.png";
			//$base_url = "http://beicker.org";
			//$base_dir = "/wiki/mini";

			if (strtolower(substr($src, 0, 5)) == "http:" || strtolower(substr($src, 0, 6)) == "https:") {
				$img_url = $src;
			} else if (substr($src, 0, 1) == "/") {
				$img_url = $base_url . $src;
			} else if (substr($src, 0, 2) == "./") {
				$img_url = $base_url . substr($src, 1);
			} else if (substr($src, 0, 3) == "../") {
				$dir = $base_dir;
				while (substr($src, 0, 3) == "../") {
					$dir = dirname($dir);
					$src = substr($src, 3);
				}
				$img_url = $base_url . $dir;
				if (substr($img_url, -1) != "/") {
					$img_url .= "/";
				}
				$img_url .= $src;
			} else {
				$img_url = $base_url . $base_dir;
				if (substr($img_url, -1) != "/") {
					$img_url .= "/";
				}
				$img_url .= $src;
			}
			$ext = fs_ext($img_url);
			if ($ext == "jpg" || $ext == "jpeg" || $ext == "png") {
				//print "img_url [$img_url]\n";
				$images[] = $img_url;
			}
			//die();
		}

		$beg = stripos($data, "<img ", $end + 1);
	}

	return $images;
}


function resize_image($src_img, $dst_width, $dst_height)
{
	$src_width = imagesx($src_img);
	$src_height = imagesy($src_img);

	$src_aspect = $src_width / $src_height;
	$dst_aspect = $dst_width / $dst_height;
	if ($src_aspect <= $dst_aspect) {
		//print "width driven - ";
		$src_w = $src_width;
		$src_h = round($src_width / $dst_aspect);
		$src_x = 0;
		$src_y = round($src_height / 2 - $src_h / 2);
	} else {
		//print "height driven - ";
		$src_w = round($src_height * $dst_aspect);
		$src_h = $src_height;
		$src_x = round($src_width / 2 - $src_w / 2);
		$src_y = 0;
	}

	//print "src_aspect [$src_aspect] src_width [$src_width] src_height [$src_height] src_x [$src_x] src_y [$src_y] src_w [$src_w] src_h [$src_h] dst_aspect [$dst_aspect] dst_width [$dst_width] dst_height [$dst_height]\n";
	$dst_img = imagecreatetruecolor($dst_width, $dst_height);
	imagecopyresampled($dst_img, $src_img , 0, 0, $src_x, $src_y, $dst_width, $dst_height, $src_w, $src_h);

	return $dst_img;
}


function download_image($doc_url, $img_url)
{
	global $server_name;
	global $doc_root;
	global $auth_zid;

	//print "download image - img_url [$img_url]\n";
	if (db_has_rec("tmp_image", array("original_url" => $img_url))) {
		$tmp_image = db_get_rec("tmp_image", array("original_url" => $img_url));
		return $tmp_image["tmp_image_id"];
	}

	$data = http_slurp($img_url);
	$orig_img = @imagecreatefromstring($data);
	if ($orig_img === false) {
		return 0;
	}
	$hash = crypt_sha256($data);
	$data = "";
	$width = imagesx($orig_img);
	$height = imagesy($orig_img);
	if ($width < 256 || $height < 256) {
		return 0;
	}

	// set background of transparent images to white instead of black
	$src_img = imagecreatetruecolor($width, $height);
	$white = imagecolorallocate($src_img, 255, 255, 255);
	imagefilledrectangle($src_img, 0, 0, $width, $height, $white);
	imagecopy($src_img, $orig_img, 0, 0, 0, 0, $width, $height);
	imagedestroy($orig_img);

	$tmp_image = array();
	$tmp_image["tmp_image_id"] = 0;
	$tmp_image["hash"] = $hash;
	$tmp_image["original_url"] = $img_url;
	$tmp_image["original_width"] = $width;
	$tmp_image["original_height"] = $height;
	$tmp_image["parent_url"] = $doc_url;
	$tmp_image["server"] = gethostname() . ".$server_name";
	$tmp_image["time"] = time();
	$tmp_image["zid"] = $auth_zid;
	db_set_rec("tmp_image", $tmp_image);
	$tmp_image = db_get_rec("tmp_image", array("original_url" => $img_url));

	//print "tmp_image_id [" . $tmp_image["tmp_image_id"] . "]\n";
	//print "size [" . strlen($data) . "]\n";

	$id = $tmp_image["tmp_image_id"];
	$path = $doc_root . public_path($tmp_image["time"]); //$id, "t");
	//print "source path [$path]\n";
	//die();
	if (!is_dir($path)) {
		mkdir($path, 0755, true);
	}
	//$ext = fs_ext($img_url);
	//fs_slap("$path/t$id.$ext", $data);

	$tmp_img = resize_image($src_img, 128, 128);
	imagejpeg($tmp_img, "$path/t$id.128x128.jpg");
	imagedestroy($tmp_img);

	$tmp_img = resize_image($src_img, 256, 256);
	imagejpeg($tmp_img, "$path/t$id.256x256.jpg");
	imagedestroy($tmp_img);

	return $id;
}


function promote_image($tmp_image_id)
{
	global $doc_root;

	$tmp_image = db_get_rec("tmp_image", $tmp_image_id);
	$path = public_path($tmp_image["time"]);
	//$ext = fs_ext($tmp_image["original_url"]);

	//$file = "$doc_root$path/t$tmp_image_id.$ext";
	//$data = fs_slurp($file);
	//$hash = crypt_sha256($data);
	//$src_img = imagecreatefromstring($data);
	//if ($src_img === false) {
	//	die("unable to open [$file]");
	//}

	//return create_image($src_img, $tmp_image, $hash);

	$image = array();
	$image["image_id"] = 0;
	$image["hash"] = $tmp_image["hash"];
	$image["original_width"] = $tmp_image["original_width"];
	$image["original_height"] = $tmp_image["original_height"];
	$image["original_url"] = $tmp_image["original_url"];
	$image["parent_url"] = $tmp_image["parent_url"];
	$image["server"] = $tmp_image["server"];
	$image["time"] = $tmp_image["time"];
	$image["zid"] = $tmp_image["zid"];
	db_set_rec("image", $image);

	$image = db_get_rec("image", array("zid" => $tmp_image["zid"], "time" => $tmp_image["time"]));
	$image_id = $image["image_id"];

	fs_rename("$doc_root$path/t$tmp_image_id.128x128.jpg", "$doc_root$path/i$image_id.128x128.jpg");
	fs_rename("$doc_root$path/t$tmp_image_id.256x256.jpg", "$doc_root$path/i$image_id.256x256.jpg");

	db_del_rec("tmp_image", $tmp_image_id);

	return $image_id;
}

/*
function create_image($src_img, $tmp_image, $hash)
{
	global $doc_root;
	global $server_name;
	global $auth_zid;

	$original_width = imagesx($src_img);
	$original_height = imagesy($src_img);
	$original_url = $tmp_image["original_url"];
	$parent_url = $tmp_image["parent_url"];
	$time = $tmp_image["time"];
	$server = $tmp_image["server"];
	$path = public_path($time);

	if ($original_width < 256 || $original_height < 256) {
		die("image must be at least 256 x 256");
	}

	$res = array();
	$res[] = array(128, 128, 1, 1);
	$res[] = array(256, 256, 1, 1);

	$image = array();
	$image["image_id"] = 0;
	$image["hash"] = $hash;
	$image["original_width"] = $original_width;
	$image["original_height"] = $original_height;
	$image["original_url"] = $original_url;
	$image["parent_url"] = $parent_url;
	$image["server"] = $server;
	$image["time"] = $time;
	$image["zid"] = $auth_zid;
	db_set_rec("image", $image);
	$image = db_get_rec("image", array("zid" => $zid, "time" => $time));
	$image_id = $image["image_id"];

	for ($i = 0; $i < count($res); $i++) {
		$w = $res[$i][0];
		$h = $res[$i][1];
		$aw = $res[$i][2];
		$ah = $res[$i][3];

		$tmp_img = resize_image($src_img, $w, $h);
		$file = "$doc_root$path/i$image_id.$w" . "x" . "$h.jpg";
		if (fs_is_file($file)) {
			fs_unlink($file);
		}
		imagejpeg($tmp_img, $file);
		imagedestroy($tmp_img);
	}

	return $image_id;
}
*/

function create_photo($src_img, $original_name, $hash)
{
	global $doc_root;
	global $server_name;
	global $auth_zid;

	$time = time();
	$path = public_path($time);
	$original_width = imagesx($src_img);
	$original_height = imagesy($src_img);

	if ($original_width < 320 || $original_height < 180) {
		die("photo must be at least 320 x 180");
	}

	$res = array();
	$res[] = array(128, 128, 1, 1);
	$res[] = array(256, 256, 1, 1);

	$res[] = array(320, 320, 1, 1);
	$res[] = array(640, 640, 1, 1);
	$res[] = array(1080, 1080, 1, 1);

	$res[] = array(320, 240, 4, 3);
	$res[] = array(640, 480, 4, 3);
	$res[] = array(1440, 1080, 4, 3);

	$res[] = array(320, 180, 16, 9);
	$res[] = array(640, 360, 16, 9);
	$res[] = array(1920, 1080, 16, 9);

	$res[] = array(320, 427, 3, 4);
	$res[] = array(640, 853, 3, 4);
	$res[] = array(1080, 1440, 3, 4);

	$res[] = array(320, 569, 9, 16);
	$res[] = array(640, 1138, 9, 16);
	$res[] = array(1080, 1920, 9, 16);

	list($aspect_width, $aspect_height) = find_aspect($original_width, $original_height);

	$photo = array();
	$photo["photo_id"] = 0;
	$photo["aspect_height"] = $aspect_height;
	$photo["aspect_width"] = $aspect_width;
	$photo["hash"] = $hash;
	$photo["original_width"] = $original_width;
	$photo["original_height"] = $original_height;
	$photo["original_name"] = $original_name;
	$photo["server"] = gethostname() . ".$server_name";
	$photo["size"] = 0;
	$photo["time"] = $time;
	$photo["has_medium"] = 0;
	$photo["has_large"] = 0;
	$photo["zid"] = $auth_zid;
	db_set_rec("photo", $photo);
	$photo = db_get_rec("photo", array("zid" => $auth_zid, "time" => $time));
	$photo_id = $photo["photo_id"];
	//var_dump($photo);
	//die();

	if (!is_dir("$doc_root$path")) {
		mkdir("$doc_root$path", 0755, true);
	}

	$size = 0;
	for ($i = 0; $i < count($res); $i++) {
		$w = $res[$i][0];
		$h = $res[$i][1];
		$aw = $res[$i][2];
		$ah = $res[$i][3];
		if ($original_width >= $w && $original_height >= $h && ($w <= 256 || ($aw == $aspect_width && $ah == $aspect_height))) {
			if ($w == 640) {
				$photo["has_medium"] = 1;
			}
			if ($w > 640) {
				$photo["has_large"] = 1;
			}
			$tmp_img = resize_image($src_img, $w, $h);
			$file = "$doc_root$path/p$photo_id.$w" . "x" . "$h.jpg";
			if (fs_is_file($file)) {
				fs_unlink($file);
			}
			imagejpeg($tmp_img, $file);
			imagedestroy($tmp_img);
			$size += fs_size($file);
		}
	}

	$photo["size"] = $size;
	db_set_rec("photo", $photo);
	//die("here");

	return $photo_id;
}


function find_aspect($original_width, $original_height)
{
	$aspect = $original_width / $original_height;

	if ($aspect < 0.65) {
		$aspect_width = 9;
		$aspect_height = 16;
	} else if ($aspect < 0.85) {
		$aspect_width = 3;
		$aspect_height = 4;
	} else if ($aspect < 1.15) {
		$aspect_width = 1;
		$aspect_height = 1;
	} else if ($aspect < 1.55) {
		$aspect_width = 4;
		$aspect_height = 3;
	} else {
		$aspect_width = 16;
		$aspect_height = 9;
	}

	return array($aspect_width, $aspect_height);
}


function clean_tmp_images()
{
	global $doc_root;

	$row = run_sql("select tmp_image_id, time, original_url from tmp_image where time < ?", array(time() - 60 * 60));
	for ($i = 0; $i < count($row); $i++) {
		$tmp_image_id = $row[$i]["tmp_image_id"];
		$time = $row[$i]["time"];
		$path = public_path($time);
		//$ext = fs_ext($row[$i]["original_url"]);

		//print "id [" . $row[$i]["tmp_image_id"] . "]\n";
		//print "unlink [" . $path . "/t$tmp_image_id.128x128.jpg" . "]\n";
		//print "unlink [" . $path . "/t$tmp_image_id.$ext" . "]\n";

		fs_unlink("$doc_root$path/t$tmp_image_id.128x128.jpg");
		fs_unlink("$doc_root$path/t$tmp_image_id.256x256.jpg");

		db_del_rec("tmp_image", $tmp_image_id);
	}
}

