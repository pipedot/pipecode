<?

$category_id = (int) $s3;
$store_category = db_get_rec("store_category", $category_id);

$feature_id = http_get_int("feature_id");
$store_feature = db_get_rec("store_feature", $feature_id);

print_header("Edit Feature");
beg_main();
beg_form();

beg_tab("Edit Feature");
print_row(array("caption" => "Name", "text_key" => "feature_name", "text_value" => $store_feature["feature_name"]));
print_row(array("caption" => "Description", "textarea_key" => "feature_description", "textarea_value" => $store_feature["feature_description"]));
end_tab();

right_box("Delete,Save");

end_form();
end_main();
print_footer();
