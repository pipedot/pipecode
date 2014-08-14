<?

if (@$_POST["save"] != "") {
	$category_name = http_post_string("category_name", array("len" => 50, "valid" => "[a-z][A-Z] "));
	$category_description = http_post_string("category_description", array("len" => 50, "valid" => "[a-z][A-Z],- "));
	$category_icon = http_post_string("category_icon", array("len" => 50, "valid" => "[a-z][0-9]_-. "));

	run_sql("insert into store_category_list (category_name, category_description, category_icon) values (?, ?, ?)", array($category_name, $category_description, $category_icon));
	header("Location: category_list");
	die();
}

print_header("Store");
beg_main();
beg_form();

beg_tab("Add Category");
print_row(array("caption" => "Name", "text_key" => "category_name"));
print_row(array("caption" => "Description", "text_key" => "category_description"));
print_row(array("caption" => "Icon", "text_key" => "category_icon"));
end_tab();

right_box("Save");

end_form();
end_main();
print_footer();
