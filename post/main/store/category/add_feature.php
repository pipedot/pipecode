<?

$category_id = (int) $s3;
$store_category = db_get_rec("store_category", $category_id);

$feature_name = http_post_string("feature_name", array("len" => 50, "valid" => "[a-z][A-Z] "));
$feature_description = http_post_string("feature_description", array("len" => 5000, "valid" => "[a-z][A-Z][0-9],-.\"'% "));

$store_feature = array();
$store_feature["feature_id"] = 0;
$store_feature["category_id"] = $category_id;
$store_feature["feature_name"] = $feature_name;
$store_feature["feature_description"] = $feature_description;
db_set_rec("store_feature", $store_feature);

header("Location: ./");
