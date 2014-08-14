<?

print_header("Store");

$item_id = http_get_int("item_id");
$row = run_sql("select * from store_item where item_id = ?", array($item_id));
if (count($row) == 0) {
	die("item not found [$item_id]");
}
$image_id = $row[0]["image_id"];
$title = $row[0]["title"];
$description = $row[0]["description"];

writeln('<table cellspacing="0" cellpadding="8" width="100%">');
writeln('	<tr>');
writeln('		<td rowspan="3" valign="top" width="200">');
writeln('			<a href="image?item_id=' . $item_id . '"><img id="big" alt="product image" class="store_image_400" src="/images/store/image/' . $image_id . '-400.jpg"/></a>');
writeln('			<div style="margin-top: 12px; text-align: center">');
$row = run_sql("select image_id from store_image where item_id = ?", array($item_id));
for ($i = 0; $i < count($row); $i++) {
	writeln('				<a href="image?item_id=' . $item_id . '&image_id=' . $row[$i]["image_id"] . '"><img alt="thumbnail" class="store_thumb_40" src="/images/store/image/' . $row[$i]["image_id"] . '-40.jpg" onmouseover="document.getElementById(\'big\').src = \'/images/store/image/' . $row[$i]["image_id"] . '-400.jpg\'"/></a>');
}
$row = run_sql("select image_id from store_item_image where item_id = ?", array($item_id));
for ($i = 0; $i < count($row); $i++) {
	writeln('				<a href="image?item_id=' . $item_id . '&image_id=' . $row[$i]["image_id"] . '"><img alt="thumbnail" class="store_thumb_40" src="/images/store/image/' . $row[$i]["image_id"] . '-40.jpg" onmouseover="document.getElementById(\'big\').src = \'/images/store/image/' . $row[$i]["image_id"] . '-400.jpg\'"/></a>');
}
writeln('			</div>');
writeln('		</td>');
writeln('		<td colspan="2" valign="top" class="store_item_title">' . $title . '</td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td valign="top" class="store_item_tag">Description:</td>');
writeln('		<td valign="top">' . $description . '</td>');
writeln('	</tr>');
writeln('	<tr>');
writeln('		<td valign="top" class="store_item_tag">Features:</td>');
writeln('		<td valign="top">');


writeln('			<table class="outline" cellspacing="0" cellpadding="4" width="100%">');
$row = run_sql("select feature_name, value, feature_description from store_item_feature inner join store_feature on store_item_feature.feature_id = store_feature.feature_id where item_id = ?", array($item_id));
for ($i = 0; $i < count($row); $i++) {
	writeln('				<tr><td>' . $row[$i]["feature_name"] . '</td><td>' . $row[$i]["value"] . '</td></tr>');
}
writeln('			</table>');


for ($i = 0; $i < count($row); $i++) {
	writeln('			<table class="outline" cellspacing="0" cellpadding="4" width="100%">');
	writeln('				<tr>');
	writeln('					<th colspan="2">' . $row[$i]["feature_name"] . '</th>');
	writeln('				</tr>');
	writeln('				<tr>');
	$image = str_replace(" ", "_", strtolower($row[$i]["feature_name"]));
	if (!is_file("../images/store/feature/$image.png")) {
		$image = "blank";
	}
	writeln('					<td width="108"><img src="/images/store/feature/' . $image . '.png"/></td>');
	writeln('					<td>' . $row[$i]["feature_description"] . '</td>');
	writeln('				</tr>');
	writeln('			</table>');
}


writeln('		</td>');
writeln('	</tr>');

writeln('</table>');

print_footer();
