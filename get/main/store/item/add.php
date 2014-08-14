<?

include("../../common.php");

check_auth("admin");

if (@$_POST["save"] != "") {
	$category_id = http_post_int("category_id");
	$featured = http_post_int("featured");
	$name = http_post_string("name", array("len" => 50, "valid" => "[a-z][A-Z][0-9]-. "));
	$title = http_post_string("title", array("len" => 250, "valid" => "[a-z][A-Z][0-9]-. "));
	$price = http_post_string("price", array("len" => 10, "valid" => "[0-9]."));
	$shipping = http_post_string("shipping", array("len" => 10, "valid" => "[0-9]."));
	$description = http_post_string("description", array("len" => 5000, "valid" => "[a-z][A-Z][0-9],-.\n "));

	run_sql("insert into store_item (category_id, featured, name, title, price, shipping, description) values (?, ?, ?, ?, ?, ?, ?)", array($category_id, $featured, $name, $title, $price, $shipping, $description));
	header("Location: ./");
	die();
}

print_header(array("name1" => "Store", "link1" => "store/"));

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
single_tab("Add Item");
print_row(array("caption" => "Name", "text_key" => "name"));
print_row(array("caption" => "Title", "text_key" => "title"));
print_row(array("caption" => "Description", "textarea_key" => "description"));
print_row(array("caption" => "Category", "option_key" => "category_id", "option_list" => $category_list, "option_keys" => $category_keys));
print_row(array("caption" => "Featured", "option_key" => "featured", "option_list" => $featured_list, "option_keys" => $featured_keys, "option_value" => 3));
print_row(array("caption" => "Price", "text_key" => "price", "text_value" => "0"));
print_row(array("caption" => "Shipping", "text_key" => "shipping", "text_value" => "0"));
end_tab();
writeln('<div class="right_box">');
writeln('<input type="submit" name="save" value="Save"/>');
writeln('</div>');
writeln('</form>');

print_footer();

?>
