<?

include("../common.php");

check_auth("admin");

$image_id = get_int("image_id");
$row = run_sql("select item_id from store_image_list where image_id = ?", array($image_id));
if (count($row) == 0) {
	die("image not found [$image_id]");
}
$item_id = $row[0]["item_id"];

if (@$_POST["sure"] != "") {
	run_sql("delete from store_image_list where image_id = ?", array($image_id));
	run_sql("delete from store_item_image where image_id = ?", array($image_id));

	$a = array(1200, 800, 400, 100, 40);
	for ($i = 0; $i < count($a); $i++) {
		$file = "$doc_root/www/images/store/image/$image_id-" . $a[$i] . ".jpg";
		if (fs_is_file($file)) {
			fs_unlink($file);
		}
	}

	if ($item_id == 0) {
		header("Location: image_list");
	} else {
		header("Location: item_edit?item_id=$item_id");
	}
	die();
}

print_header(array("name1" => "Store", "link1" => "store/"));

writeln('<form method="post">');
writeln('<h3>Delete Image</h3>');
writeln('<p>Are you sure you want to delete this image?</p>');
writeln('<div><img class="store_image_400" style="padding-bottom: 12px" src="/images/store/image/' . $image_id . '-400.jpg"/></div>');

if ($item_id == 0) {
	$row = run_sql("select count(*) as image_count from store_item_image where image_id = ?", array($image_id));
	$image_count = $row[0]["image_count"];
	writeln('<p>This will also delete [<b>' . $image_count . '</b>] shared image references.</p>');
}

writeln('<input type="submit" name="sure" value="Delete"/>');
writeln('</form>');

print_footer();

?>
