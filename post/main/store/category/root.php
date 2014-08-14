<?

$category_id = (int) $s3;
$store_category = db_get_rec("store_category", $category_id);

if (http_post("delete")) {
/*	$row = run_sql("select count(*) as item_count from store_item where category_id = ?", array($category_id));
	$item_count = $row[0]["item_count"];

	$row = run_sql("select count(*) as feature_count from store_feature where category_id = ?", array($category_id));
	$feature_count = $row[0]["feature_count"];

	$row = run_sql("select count(item_id) as item_feature_count from store_item_feature inner join store_feature on store_item_feature.feature_id = store_feature.feature_id where category_id = ?", array($category_id));
	$item_feature_count = $row[0]["item_feature_count"];

	print_header("Delete Category");
	beg_main();
	beg_form();
	writeln('<p>Are you sure you want to delete the [<b>' . $store_category["category_name"] . '</b>] category?</p>');
	writeln('<p>This will also delete [<b>' . $item_count . '</b>] store items, [<b>' . $feature_count . '</b>] category features, and [<b>' . $item_feature_count . '</b>] item features.</p>');
	left_box("Delete");
	end_form();
	end_main();
	print_footer();
	die();
}
if (http_post("sure")) {*/
	run_sql("delete from store_item where category_id = ?", array($category_id));

	$row = run_sql("select feature_id from store_feature where category_id = ?", array($category_id));
	for ($i = 0; $i < count($row); $i++) {
		run_sql("delete from store_item_feature where feature_id = ?", array($row[$i]["feature_id"]));
	}
	run_sql("delete from store_feature where category_id = ?", array($category_id));

	run_sql("delete from store_category where category_id = ?", array($category_id));
	header("Location: ../");
	die();
}
if (http_post("save")) {
	$category_name = http_post_string("category_name", array("len" => 50, "valid" => "[a-z][A-Z] "));
	$category_description = http_post_string("category_description", array("len" => 50, "valid" => "[a-z][A-Z],- "));
	$category_icon = http_post_string("category_icon", array("len" => 50, "valid" => "[a-z][0-9]_"));

	$store_category = array();
	$store_category["category_id"] = $category_id;
	$store_category["category_name"] = $category_name;
	$store_category["category_description"] = $category_description;
	$store_category["category_icon"] = $category_icon;
	db_set_rec("store_category", $store_category);

	header("Location: ../");
	die();
}
