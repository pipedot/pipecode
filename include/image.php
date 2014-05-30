<?

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

	//print "download image - img_url [$img_url]\n";
	if (db_has_rec("tmp_image", array("original_url" => $img_url))) {
		$tmp_image = db_get_rec("tmp_image", array("original_url" => $img_url));
		return $tmp_image["tmp_image_id"];
	}

	//print "before slurp\n";
	$data = http_slurp($img_url);
	//print "after slurp - len [" . strlen($data) . "]\n";
	$src_img = @imagecreatefromstring($data);
	//print "after gd\n";
	if ($src_img === false) {
		//print "bad image [$img_url]";
		//die();
		return 0;
	}
	$width = imagesx($src_img);
	$height = imagesy($src_img);
	//print "width [$width] height [$height]\n";
	if ($width < 320 || $height < 180) {
		return 0;
	}
	//print "here\n";

	$tmp_image = array();
	$tmp_image["tmp_image_id"] = 0;
	$tmp_image["original_url"] = $img_url;
	$tmp_image["original_width"] = $width;
	$tmp_image["original_height"] = $height;
	$tmp_image["parent_url"] = $doc_url;
	$tmp_image["server"] = gethostname() . ".$server_name";
	$tmp_image["time"] = time();
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
	$ext = fs_ext($img_url);
	fs_slap("$path/t$id.$ext", $data);

	$tmp_img = resize_image($src_img, 128, 128);
	imagejpeg($tmp_img, "$path/t$id.128x128.jpg", 80);
	imagedestroy($tmp_img);

	return $id;
}


function promote_image($tmp_image_id)
{
	global $doc_root;

	$tmp_image = db_get_rec("tmp_image", $tmp_image_id);
	$path = public_path($tmp_image["time"]);
	$ext = fs_ext($tmp_image["original_url"]);

	$file = "$doc_root$path/t$tmp_image_id.$ext";
	//die("file [$file]");
	$data = fs_slurp($file);
	//print "file name [$file] len [" . strlen($data) . "]\n";
	$src_img = imagecreatefromstring($data);
//	if ($ext == "jpg") {
//		$src_img = imagecreatefromjpeg($file);
//	} else {
//		$src_img = imagecreatefrompng($file);
//	}
	if ($src_img === false) {
		die("unable to open [$file]");
	}
	$original_width = imagesx($src_img);
	$original_height = imagesy($src_img);

	$res = array();
	//$res[] = array(64, 64, 1, 1);
	$res[] = array(128, 128, 1, 1);
	$res[] = array(160, 90, 16, 9);
	$res[] = array(160, 120, 4, 3);
	$res[] = array(160, 160, 1, 1);
	$res[] = array(320, 180, 16, 9);
	$res[] = array(320, 240, 4, 3);
	$res[] = array(320, 320, 1, 1);
	$res[] = array(640, 360, 16, 9);
	$res[] = array(640, 480, 4, 3);
	$res[] = array(640, 640, 1, 1);

	$aspect = $original_width / $original_height;
	//print "aspect [$aspect]\n";
	if ($aspect < 1.2) {
		$aspect_width = 1;
		$aspect_height = 1;
	} else if ($aspect < 1.5) {
		$aspect_width = 4;
		$aspect_height = 3;
	} else {
		$aspect_width = 16;
		$aspect_height = 9;
	}
	//print "aspect class [$aspect_width:$aspect_height]\n";

	if (db_has_rec("image", array("original_url" => $tmp_image["original_url"], "time" => $tmp_image["time"]))) {
		$image = db_get_rec("image", array("original_url" => $tmp_image["original_url"], "time" => $tmp_image["time"]));
	} else {
		$image = array();
		$image["image_id"] = 0;
		$image["aspect_height"] = $aspect_height;
		$image["aspect_width"] = $aspect_width;
		for ($i = 0; $i < count($res); $i++) {
			$w = $res[$i][0];
			$h = $res[$i][1];
			$aw = $res[$i][2];
			$ah = $res[$i][3];
			//if ($w > 128) {
			//	if ($aw == $aspect_width && $ah == $aspect_height) {
			//		$image["has_" . $w . "x" . $h] = 1;
			//	} else {
			//		$image["has_" . $w . "x" . $h] = 0;
			//	}
			//}
		}
		$image["original_width"] = $original_width;
		$image["original_height"] = $original_height;
		$image["original_url"] = $tmp_image["original_url"];
		$image["parent_url"] = $tmp_image["parent_url"];
		$image["server"] = $tmp_image["server"];
		$image["size"] = 0;
		$image["time"] = $tmp_image["time"];
		$image["has_640"] = 0;
		db_set_rec("image", $image);
		$image = db_get_rec("image", array("original_url" => $tmp_image["original_url"], "time" => $tmp_image["time"]));
	}
	$image_id = $image["image_id"];

	$size = 0;
	for ($i = 0; $i < count($res); $i++) {
		$w = $res[$i][0];
		$h = $res[$i][1];
		$aw = $res[$i][2];
		$ah = $res[$i][3];
		if ($original_width >= $w && $original_height >= $h && ($w <= 128 || ($aw == $aspect_width && $ah == $aspect_height))) {
			//if ($w > 128) {
			//	$image["has_$w" . "x" . $h] = 1;
			//}
			if ($w == 640) {
				$image["has_640"] = 1;
			}
			$tmp_img = resize_image($src_img, $w, $h);
			$file = "$doc_root/$path/i$image_id.$w" . "x" . "$h.jpg";
			if (is_file($file)) {
				unlink($file);
			}
			//imagejpeg($tmp_img, $file, 80);
			imagejpeg($tmp_img, $file);
			//$file = "$doc_root/$path/i$image_id.$w" . "x" . "$h.png";
			//imagepng($tmp_img, $file);
			imagedestroy($tmp_img);
			$size += fs_size($file);
		}
	}

	$image["size"] = $size;
	db_set_rec("image", $image);

	return $image_id;
}


function clean_tmp_images()
{
	global $doc_root;

	$row = run_sql("select tmp_image_id, time, original_url from tmp_image where time < ?", array(time() - 60 * 60));
	for ($i = 0; $i < count($row); $i++) {
		$tmp_image_id = $row[$i]["tmp_image_id"];
		$time = $row[$i]["time"];
		$path = public_path($time);
		$ext = fs_ext($row[$i]["original_url"]);

		//print "id [" . $row[$i]["tmp_image_id"] . "]\n";
		//print "unlink [" . $path . "/t$tmp_image_id.128x128.jpg" . "]\n";
		//print "unlink [" . $path . "/t$tmp_image_id.$ext" . "]\n";

		fs_unlink("$doc_root/$path/t$tmp_image_id.128x128.jpg");
		fs_unlink("$doc_root/$path/t$tmp_image_id.$ext");

		db_del_rec("tmp_image", $tmp_image_id);
	}
}

