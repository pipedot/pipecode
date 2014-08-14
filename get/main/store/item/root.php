<?

$item_id = (int) $s3;
$store_item = db_get_rec("store_item", $item_id);


print_header("Item");
beg_main();

$row = run_sql("select category_id, category_name from store_category order by category_name");
$category_keys = array();
$category_list = array();
for ($i = 0; $i < count($row); $i++) {
	$category_keys[] = $row[$i]["category_id"];
	$category_list[] = $row[$i]["category_name"];
}

$featured_keys = array();
$featured_list = array();
for ($i = 1; $i <= 5; $i++) {
	$featured_keys[] = $i;
	$featured_list[] = $i;
}

writeln('<form method="post">');
beg_tab("Edit Item");
print_row(array("caption" => "Category", "option_key" => "category_id", "option_list" => $category_list, "option_keys" => $category_keys, "option_value" => $store_item["category_id"]));
print_row(array("caption" => "Featured", "option_key" => "featured", "option_list" => $featured_list, "option_keys" => $featured_keys, "option_value" => $store_item["featured"]));
print_row(array("caption" => "Name", "text_key" => "name", "text_value" => $store_item["name"]));
print_row(array("caption" => "Title", "text_key" => "title", "text_value" => $store_item["title"]));
print_row(array("caption" => "Price", "text_key" => "price", "text_value" => $store_item["price"]));
print_row(array("caption" => "Shipping", "text_key" => "shipping", "text_value" => $store_item["shipping"]));
writeln('	<tr>');
writeln('		<td>');
writeln('			<table cellpadding="0" cellspacing="0" width="100%">');
writeln('				<tr>');
writeln('					<td width="140">Image</td>');
writeln('					<td><a href="pick_image">' . ($store_item["image_id"] == 0 ? 'None' : '<img alt="item thumbnail" src="/images/store/item/' . $store_item["image_id"] . '-100.jpg"/>') . '</a></td>');
writeln('				</tr>');
writeln('			</table>');
writeln('		</td>');
writeln('	</tr>');
print_row(array("caption" => "Description", "textarea_key" => "description", "textarea_value" => $store_item["description"]));
end_tab();

beg_tab("Features");
//$row = run_sql("select store_feature.feature_id, feature_name, value from store_feature left join store_item_feature on store_feature.feature_id = store_feature.feature_id where category_id = ? and item_id = ?", array($category_id, $item_id));
$row1 = run_sql("select feature_id, feature_name from store_feature where category_id = ?", array($store_item["category_id"]));
for ($i = 0; $i < count($row1); $i++) {
	$row2 = run_sql("select value from store_item_feature where feature_id = ?", array($row1[$i]["feature_id"]));
	if (count($row2) == 0) {
		$value = "";
	} else {
		$value = $row2[0]["value"];
	}
	print_row(array("caption" => $row1[$i]["feature_name"], "text_key" => "feature_" . $row1[$i]["feature_id"], "text_value" => $value));
}
end_tab();

right_box("Delete,Save");

$row = run_sql("select image_id from store_image where item_id = ?", array($item_id));
beg_tab("Images", array("colspan" => 3));
if (count($row) == 0) {
	writeln('<tr><td colspan="3">None</td></tr>');
}
for ($i = 0; $i < count($row); $i++) {
	writeln('	<tr>');
	writeln('		<td width="80%"><a href="/images/store/image/' . $row[$i]["image_id"] . '-800.jpg"><img class="store_image_40" src="/images/store/image/' . $row[$i]["image_id"] . '-40.jpg"/></a></td>');
	writeln('		<td width="20%" align="center"><a href="image_delete?image_id=' . $row[$i]["image_id"] . '"><span class="icon_16" style="background-image: url(/images/no-16.png);">Delete</span></a></td>');
	writeln('	</tr>');
}
end_tab();
right_box('<a href="../../image/add?item_id=' . $item_id . '">Add Image</a>');

end_form();
end_main();
print_footer();
