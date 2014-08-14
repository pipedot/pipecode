<?

$category_id = (int) $s3;
$store_category = db_get_rec("store_category", $category_id);

$feature_id = http_get_int("feature_id");
$store_feature = db_get_rec("store_feature", $feature_id);

if (http_post("delete")) {
/*	$row = run_sql("select count(*) as feature_count from store_item_feature where feature_id = ?", array($feature_id));
	$feature_count = $row[0]["feature_count"];

	print_header(array("name1" => "Store", "link1" => "store/", "name2" => "Category", "link2" => "category/", "name3" => $category_name, "link3" => "$category_id/"));
	writeln('<form method="post">');
	writeln('<p>Are you sure you want to delete the [<b>' . $feature_name . '</b>] feature?</p>');
	writeln('<p>This will also delete [<b>' . $feature_count . '</b>] item features.</p>');
	writeln('<input type="submit" name="sure" value="Delete"/>');
	print_footer();
	die();
}
if (@$_POST["sure"] != "") {*/
	run_sql("delete from store_item_feature where feature_id = ?", array($feature_id));
	run_sql("delete from store_feature where feature_id = ?", array($feature_id));
	header("Location: ./");
	die();
}
if (http_post("save")) {
	$feature_name = http_post_string("feature_name", array("len" => 50, "valid" => "[a-z][A-Z] "));
	$feature_description = http_post_string("feature_description", array("len" => 5000, "valid" => "[a-z][A-Z][0-9],-.\"'% "));

	$store_feature["feature_name"] = $feature_name;
	$store_feature["feature_description"] = $feature_description;
	db_set_rec("store_feature", $store_feature);

	header("Location: ./");
	die();
}
