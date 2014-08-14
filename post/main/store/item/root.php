<?

$item_id = (int) $s3;
$store_item = db_get_rec("store_item", $item_id);

if (http_post("delete")) {
/*
	$row = run_sql("select count(*) as image_count from store_item_image where item_id = ?", array($item_id));
	$image_count = $row[0]["image_count"];

	print_header(array("name1" => "Store", "link1" => "store/"));
	writeln('<form method="post">');
	writeln('<p>Are you sure you want to delete the [<b>' . $name . '</b>] item?</p>');
	writeln('<p>This will also delete [<b>' . $image_count . '</b>] images.</p>');
	writeln('<input type="submit" name="sure" value="Delete"/>');
	print_footer();
	die();
}
if (@$_POST["sure"] != "") {*/
	run_sql("delete from store_item_image where item_id = ?", array($item_id));

	run_sql("delete from store_item where item_id = ?", array($item_id));
	header("Location: item_list");
	die();
}
if (http_post("save")) {
	$category_id = http_post_int("category_id");
	$featured = http_post_int("featured");
	$name = http_post_string("name", array("len" => 50, "valid" => "[a-z][A-Z][0-9]-. "));
	$title = http_post_string("title", array("len" => 250, "valid" => "[a-z][A-Z][0-9]-. "));
	$price = http_post_string("price", array("len" => 10, "valid" => "[0-9]."));
	$shipping = http_post_string("shipping", array("len" => 10, "valid" => "[0-9]."));
	$description = http_post_string("description", array("len" => 5000, "valid" => "[a-z][A-Z][0-9],-.\n "));

	$store_item["category_id"] = $category_id;
	$store_item["featured"] = $featured;
	$store_item["name"] = $name;
	$store_item["title"] = $title;
	$store_item["price"] = $price;
	$store_item["shipping"] = $shipping;
	$store_item["description"] = $description;
	db_set_rec("store_item", $store_item);

	$row1 = run_sql("select feature_id, feature_name from store_feature where category_id = ?", array($category_id));
	for ($i = 0; $i < count($row1); $i++) {
		$feature_id = $row1[$i]["feature_id"];
		$value = http_post_string("feature_" . $feature_id, array("required" => false, "len" => 100, "valid" => "[a-z][A-Z][0-9],-. "));
		$row2 = run_sql("select value from store_item_feature where item_id = ? and feature_id = ?", array($item_id, $feature_id));
		if (count($row2) == 0) {
			if ($value != "") {
				run_sql("insert into store_item_feature (item_id, feature_id, value) values (?, ?, ?)", array($item_id, $feature_id, $value));
			}
		} else {
			if ($value == "") {
				run_sql("delete from store_item_feature where item_id = ? and feature_id = ?", array($item_id, $feature_id));
			} else {
				run_sql("update store_item_feature set value = ? where item_id = ? and feature_id = ?", array($value, $item_id, $feature_id));
			}
		}
	}

	header("Location: ../");
	die();
}

