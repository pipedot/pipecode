<?

//$category_id = get_int("category_id");
//$row = run_sql("select category_name, category_description, category_icon from store_category_list where category_id = ?", array($category_id));
//if (count($row) == 0) {
//	die("category not found [$category_id]");
//}
//$category_name = $row[0]["category_name"];
//$category_description = $row[0]["category_description"];
//$category_icon = $row[0]["category_icon"];
$category_id = (int) $s3;
$store_category = db_get_rec("store_category", $category_id);



print_header("Store");
beg_main();
beg_form();

beg_tab("Edit Category");
print_row(array("caption" => "Name", "text_key" => "category_name", "text_value" => $store_category["category_name"]));
print_row(array("caption" => "Description", "text_key" => "category_description", "text_value" => $store_category["category_description"]));
print_row(array("caption" => "Icon", "text_key" => "category_icon", "text_value" => $store_category["category_icon"]));
end_tab();

right_box("Delete,Save");

beg_tab("Feature List");
$row = run_sql("select feature_id, feature_name from store_feature where category_id = ? order by feature_name", array($category_id));
for ($i = 0; $i < count($row); $i++) {
	writeln('	<tr class="t' . $r . '"><td><a href="edit_feature?feature_id=' . $row[$i]["feature_id"] . '"><div class="menu_item">' . $row[$i]["feature_name"] . '</div></a></td></tr>');
}
end_tab();

right_box('<a href="add_feature">Add Feature</a>');

end_form();
end_main();
print_footer();
