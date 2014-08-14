<?

include("../common.php");

check_auth("admin");

$image_id = get_int("image_id");
$row = run_sql("select item_id from store_image where image_id = ?", array($image_id));
if (count($row) == 0) {
	die("image not found [$image_id]");
}
$item_id = $row[0]["item_id"];

if (@$_POST["sure"] != "") {
	run_sql("update store_image set item_id = 0 where image_id = ?", array($image_id));
	header("Location: item_edit?item_id=$item_id");
	die();
}

print_header(array("name1" => "Store", "link1" => "store/"));

writeln('<form method="post">');
writeln('<h3>Share Image</h3>');
writeln('<p>Are you sure you want to share this image?</p>');
writeln('<div><img class="store_image_400" src="/images/store/image/' . $image_id . '-400.jpg"/></div>');
writeln('<input type="submit" name="sure" value="Share"/>');
writeln('</form>');

print_footer();

?>
