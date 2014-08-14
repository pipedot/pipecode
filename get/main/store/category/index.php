<?

print_header("Category");
beg_main();

$list = db_get_list("store_category", "category_name");
$keys = array_keys($list);
//$row = run_sql("select category_id, category_name, category_description from store_category order by category_name");
beg_tab("Category List");
//for ($i = 0; $i < count($row); $i++) {
for ($i = 0; $i < count($list); $i++) {
	$store_category = $list[$keys[$i]];
	//print_row(array("caption" => $row[$i]["category_name"], "description" => $row[$i]["category_description"], "icon" => "usb", "link" => $row[$i]["category_id"] . "/"));
	print_row(array("caption" => $store_category["category_name"], "description" => $store_category["category_description"], "icon" => $store_category["category_icon"], "link" => $keys[$i] . "/"));
}
end_tab();
right_box('<a href="add">Add Category</a>');

end_main();
print_footer();
