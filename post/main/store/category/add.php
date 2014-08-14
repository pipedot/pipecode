<?

$category_name = http_post_string("category_name", array("len" => 50, "valid" => "[a-z][A-Z] "));
$category_description = http_post_string("category_description", array("len" => 50, "valid" => "[a-z][A-Z],- "));
$category_icon = http_post_string("category_icon", array("len" => 50, "valid" => "[a-z][0-9]_"));

$store_category = array();
$store_category["category_id"] = 0;
$store_category["category_name"] = $category_name;
$store_category["category_description"] = $category_description;
$store_category["category_icon"] = $category_icon;
db_set_rec("store_category", $store_category);

header("Location: ./");
