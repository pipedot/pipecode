<?

$item_id = http_get_int("item_id");
$store_item = db_get_rec("store_item", $item_id);

print_header("Add Image");
beg_main();
beg_form("", "file");

writeln('<form method="post" enctype="multipart/form-data">');
beg_tab("Add Image");
//print_row(array("caption" => "Name", "text_key" => "name"));
writeln('	<tr>');
writeln('		<td><input type="file" name="upload"/></td>');
writeln('	</tr>');
end_tab();

right_box("Upload");

end_form();
print_footer();
