<?

$image_id = http_get_int("image_id", array("required" => false));

if (@$_POST["sure"] != "") {
	run_sql("update store_item set image_id = ? where item_id = ?", array($image_id, $item_id));
	header("Location: ./");
	die();
}
if ($image_id) {
	print_header(array("name1" => "Store", "link1" => "store/"));

	writeln('<h3>Confirm Main Image</h3>');
	writeln('<form method="post">');
	writeln('<p>Are you sure you want to set this image to be the main image for this item?</p>');
	writeln('<div><img class="store_image_400" style="padding-bottom: 12px" src="/images/store/image/' . $image_id . '-400.jpg"/></div>');
	writeln('<input type="submit" name="sure" value="Save"/>');
	writeln('</form>');

	print_footer();
	die();
}

print_header(array("name1" => "Store", "link1" => "store/"));

writeln('<h3>Select Main Image</h3>');

$row = run_sql("select image_id from store_image where item_id = ?", array($item_id));
beg_tab("Images");
writeln('<tr><td>');
if (count($row) == 0) {
	writeln('None');
}
for ($i = 0; $i < count($row); $i++) {
	writeln('<a href="pick_image?image_id=' . $row[$i]["image_id"] . '"><img class="store_item_image_40" src="/images/store/image/' . $row[$i]["image_id"] . '-40.jpg"/></a>');
}
writeln('</td></tr>');
end_tab();

$row = run_sql("select image_id from store_item_image where item_id = ?", array($item_id));
beg_tab("Shared Images");
writeln('<tr><td>');
if (count($row) == 0) {
	writeln('None');
}
for ($i = 0; $i < count($row); $i++) {
	writeln('<a href="pick_image?image_id=' . $row[$i]["image_id"] . '"><img class="store_item_image_40" src="/images/store/image/' . $row[$i]["image_id"] . '-40.jpg"/></a>');
}
writeln('</td></tr>');
end_tab();

print_footer();
