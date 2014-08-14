<?

include("../common.php");

check_auth("user");

print_header(array("name1" => "Store", "link1" => "store/", "name2" => "Software", "link2" => "software/"));

$item_id = get_int("item_id");
$image_id = get_int("image_id");

writeln('<table cellspacing="0" cellpadding="8">');
writeln('	<tr>');
writeln('		<td valign="top" width="225">');

$row = run_sql("select image_id from store_image_list where item_id = ?", array($item_id));
//if (count($row) == 0) {
//	die("no images found for item [$item_id]");
//}
for ($i = 0; $i < count($row); $i++) {
	writeln('			<a href="image?item_id=3&image_id=' . $row[$i]["image_id"] . '"><img class="store_thumb_100" src="/images/store/image/' . $row[$i]["image_id"] . '-100.jpg"/></a>');
}
$row = run_sql("select image_id from store_item_image where item_id = ?", array($item_id));
for ($i = 0; $i < count($row); $i++) {
	writeln('			<a href="image?item_id=3&image_id=' . $row[$i]["image_id"] . '"><img class="store_thumb_100" src="/images/store/image/' . $row[$i]["image_id"] . '-100.jpg"/></a>');

}

writeln('		</td>');
writeln('		<td valign="top">');
if (fs_is_file("/images/store/image/' . $image_id . '-1200.jpg")) {
	writeln('			<a href="/images/store/image/' . $image_id . '-1200.jpg"><img class="store_image_800" src="/images/store/image/' . $image_id . '-800.jpg"/></a>');
} else {
	writeln('			<img class="store_image_800" src="/images/store/image/' . $image_id . '-800.jpg"/>');
}
writeln('		</td>');
writeln('	</tr>');
writeln('</table>');

print_footer();

?>