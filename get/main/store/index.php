<?

print_header("Store");
//beg_main();

writeln('<table cellspacing="0" cellpadding="8" width="100%">');
writeln('<tr>');
writeln('<td valign="top" width="200">');
writeln('<form name="narrow" method="post">');
writeln('<table class="outline" cellspacing="0" cellpadding="4" width="100%">');
writeln('	<tr>');
writeln('		<th>Narrow Results</th>');
writeln('	</tr>');

$category_id = 1;
$r = 0;

$row = run_sql("select feature_id, feature_name from store_feature where category_id = ? order by feature_name", array($category_id));
for ($i = 0; $i < count($row); $i++) {
	writeln('	<tr class="r' . '1' . '">');
	writeln('		<td style="padding: 6px">');
	writeln('			<div class="store_item_feature">' . $row[$i]["feature_name"] . ':</div>');
	writeln('			<select name="feature_' . $row[$i]["feature_id"] . '" style="width: 100%">');

	$row2 = run_sql("select distinct value from store_item_feature inner join store_feature on store_item_feature.feature_id = store_feature.feature_id where category_id = ? and store_item_feature.feature_id = ? order by value", array($category_id, $row[$i]["feature_id"]));
	for ($j = 0; $j < count($row2); $j++) {
		writeln('				<option>' . $row2[$j]["value"] . '</option>');
	}

	writeln('			</select>');
	writeln('		</td>');
	writeln('	</tr>');

	$r = ($r ? 0 : 1);
}

writeln('</table>');
writeln('</form>');
writeln('<div class="right_box">');
writeln('<input type="submit" name="search" value="Search"/>');
writeln('</td>');
writeln('<td valign="top">');

$view = http_get_int("view", array("default" => 5));
$page = http_get_int("page", array("default" => 1));
$sort = http_get_string("sort", array("default" => "featured"));
$a = array("featured" => "Featured", "lowest" => "Lowest Price", "highest" => "Highest Price");
$b = array("featured" => "featured desc", "lowest" => "price", "highest" => "price desc");
$k = array_keys($a);
if (!array_key_exists($sort, $a)) {
	die("invalid sort order [$sort]");
}
$sort_by = $b[$sort];
writeln('<form name="sort_by" method="get">');
writeln('<table class="outline" cellspacing="0" cellpadding="8" width="100%">');
writeln('	<tr class="r1">');
writeln('		<td class="store_sort_by" width="100%">Sort by:');
writeln('			<select name="sort" onchange="document.sort_by.submit()">');
for ($i = 0; $i < count($a); $i++) {
	if ($sort == $k[$i]) {
		writeln('				<option value="' . $k[$i] . '" selected="selected">' . $a[$k[$i]] . '</option>');
	} else {
		writeln('				<option value="' . $k[$i] . '">' . $a[$k[$i]] . '</option>');
	}
}
writeln('			</select>');
writeln('		</td>');
writeln('		<td class="store_view">View:');
writeln('			<select name="view" onchange="document.sort_by.submit()">');
$a = array(5, 20, 50, 100);
for ($i = 0; $i < count($a); $i++) {
	if ($view == $a[$i]) {
		writeln('				<option selected="selected">' . $a[$i] . '</option>');
	} else {
		writeln('				<option>' . $a[$i] . '</option>');
	}
}
writeln('			</select>');
writeln('		</td>');
writeln('	</tr>');
writeln('</table>');
writeln('</form>');
//switch ($sort_by) {
//	case "":
//	case "featured":
//		$sort_by = "featured desc";
//		break;
//	case "lowest":
//		$sort_by = "price";
//		break;
//	case "highest":
//		$sort_by = "price desc";
//		break;
//	default:
//		die("invalid sort order [$sort_by]");
//}



//$row = run_sql("select count(*) as item_count from store_item where category_id = ?", array($category_id));
//$count = $row[0]["item_count"];

$r = 0;
$row = run_sql("select * from store_item where category_id = ? order by $sort_by", array($category_id));
writeln('<table class="outline" cellspacing="0" cellpadding="8" width="100%">');
$beg = ($page - 1) * $view;
$end = $beg + $view;
for ($i = $beg; $i < $end; $i++) {
	if ($i >= count($row)) {
		break;
	}
	writeln('	<tr class="r' .$r . '">');
	writeln('		<td>');
	writeln('			<img alt="product image" class="store_image_100" src="/images/store/image/' . $row[$i]["image_id"] . '-100.jpg"/>');
	writeln('		</td>');
	writeln('		<td>');
	writeln('			<table cellspacing="0" cellpadding="4" width="100%">');
	writeln('				<tr>');
	writeln('					<td colspan="2" class="store_item_title"><a href="view?item_id=' . $row[$i]["item_id"] . '">' . $row[$i]["title"] . '</a></td>');
	writeln('				</tr>');
	writeln('				<tr>');
	writeln('					<td valign="top" class="store_item_tag">Description:</td>');
	writeln('					<td>' . $row[$i]["description"] . '</td>');
	writeln('				</tr>');
	writeln('				<tr>');
	writeln('					<td valign="top" class="store_item_tag">Features:</td>');
	writeln('					<td>' . $row[$i]["features"] . '</td>');
	writeln('				</tr>');
	writeln('			</table>');
	writeln('		</td>');
	writeln('		<td style="text-align: right" valign="top">');
	writeln('			<div class="store_item_price">$' . round($row[$i]["price"]) . '<sup>.' . string_pad((($row[$i]["price"] * 100) % 100), 2) . '</sup></div>');
	writeln('			<div class="store_item_shipping">' . ($row[$i]["shipping"] == 0 ? "Free Shipping" : '$' . $row[$i]["shipping"]) . '</div>');
	writeln('			<input type="button" value="Add to Cart"/>');
	writeln('		</td>');
	writeln('	</tr>');

	$r = ($r ? 0 : 1);
}
writeln('</table>');


$pages = ceil(count($row) / $view);
writeln('<table class="outline" cellspacing="0" cellpadding="8" width="100%">');
writeln('	<tr class="r1">');
writeln('		<td class="store_page_bar">');
for ($i = 1; $i <= $pages; $i++) {
	if ($i == $page) {
		writeln('			' . $i);
	} else {
		writeln('			<a href="?sort=' . $sort . '&view=' . $view . '&page=' . $i . '">' . $i . '</a>');
	}
}
writeln('		</td>');
writeln('	</tr>');
writeln('</table>');

writeln('</td>');
writeln('</tr>');
writeln('</table>');
//writeln('<script type="text/javascript">');
//writeln('</script>');

//end_main();
print_footer();
