<?

$item_id = http_get_int("item_id");
$store_item = db_get_rec("store_item", $item_id);

if (@$_FILES["upload"]["tmp_name"] == "") {
	var_dump($_FILES);
	die("image not attached");
}
//$name = http_post_string("name", array("len" => 50, "valid" => "[a-z][A-Z][0-9]-_. "));
//$file = basename($_FILES["image"]["name"]);
$size = sys_format_size($_FILES["upload"]["size"]);
$file = "$doc_root/www/images/store/image/upload.jpg";

if (!move_uploaded_file($_FILES["upload"]["tmp_name"], $file)) {
	die("error moving temp file");
}

//$image_id = 1;
//$file = $_FILES["upload"]["tmp_name"];

list($width, $height) = getimagesize($file);

if ($width < 800 && $height < 800) {
	die("image too small - must be at least 800 pixels in at least one dimension");
}
if ($width < 1200 && $height < 1200) {
	$a = array(800, 400, 100, 40);
} else {
	$a = array(1200, 800, 400, 100, 40);
}

//$row = run_sql("select item_id from store_item where image_name = ?", array($name));
//if (count($row) != 0) {
//	die("image name already exists [$name]");
//}
run_sql("insert into store_image (item_id) values (?)", array($item_id));
$row = run_sql("select max(image_id) as image_id from store_image where item_id = ?", array($item_id));
$image_id = $row[0]["image_id"];

$source = imagecreatefromjpeg($file);
for ($i = 0; $i < count($a); $i++) {
	$w = $a[$i];
	$h = $a[$i];
	$x = 0;
	$y = 0;
	$dest = imagecreatetruecolor($w, $h);
	$color = imagecolorallocate($dest, 255, 255, 255);
	imagefilledrectangle($dest, 0, 0, $w, $h, $color);

	if ($width > $height) {
		$h = $w * ($height / $width);
		$y = ($a[$i] - $h) / 2;
	} else {
		$w = $h * ($width / $height);
		$x = ($a[$i] - $w) / 2;
	}
	imagecopyresampled($dest, $source, $x, $y, 0, 0, $w, $h, $width, $height);

	//header('Content-Type: image/jpeg');
	imagejpeg($dest, "$doc_root/www/images/store/image/$image_id-" . $a[$i] . ".jpg");
	imagedestroy($dest);
}
imagedestroy($source);
fs_unlink($file);

die("done");
