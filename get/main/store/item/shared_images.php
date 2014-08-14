<?

include("../../common.php");

check_auth("admin");
parse_query();

$row = run_sql("select image_id from store_item_image where item_id = ?", array($item_id));
$selected = array();
for ($i = 0; $i < count($row); $i++) {
	$selected[] = $row[$i]["image_id"];
}

if (@$_POST["save"] != "") {
	$row = run_sql("select image_id from store_image where item_id = 0");
	for ($i = 0; $i < count($row); $i++) {
		$image_id = $row[$i]["image_id"];
		if (in_array($image_id, $selected)) {
			if (!http_post_bool("image_$image_id")) {
				run_sql("delete from store_item_image where item_id = ? and image_id = ?", array($item_id, $image_id));
			}
		} else {
			if (http_post_bool("image_$image_id")) {
				run_sql("insert into store_item_image (item_id, image_id) values (?, ?)", array($item_id, $image_id));
			}
		}
	}
	header("Location: ./");
	die();
}

print_header(array("name1" => "Store", "link1" => "store/", "name2" => "Item", "link2" => "item/", "name3" => $item_name, "link3" => "$item_id/"));

$row = run_sql("select image_id from store_image where item_id = 0");
writeln('<form method="post">');
single_tab("Shared Images");
for ($i = 0; $i < count($row); $i++) {
	if (in_array($row[$i]["image_id"], $selected)) {
		$checked = true;
	} else {
		$checked = false;
	}
	writeln('	<tr class="t' . $r . '">');
	writeln('		<td><input type="checkbox" style="vertical-align: middle" name="image_' . $row[$i]["image_id"] . '"' . ($checked ? ' checked="true"' : '') . '/><img class="store_image_40" src="/images/store/image/' . $row[$i]["image_id"] . '-40.jpg" style="vertical-align: middle; padding-left: 6px;"/></td>');
	writeln('	</tr>');

	$r = ($r ? 0 : 1);
}
end_tab();
writeln('<div class="right_box">');
writeln('<input type="submit" name="save" value="Save"/>');
writeln('</div>');
writeln('</form>');

print_footer();

?>
