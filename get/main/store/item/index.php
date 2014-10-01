<?

print_header("Item");
beg_main();

$row = run_sql("select item_id, title from store_item order by title");
beg_tab("Item List");
for ($i = 0; $i < count($row); $i++) {
	writeln('	<tr>');
	writeln('		<td>');
	writeln('			<a href="' . $row[$i]["item_id"] . '/">');
	writeln('			<div class="menu_item">' . $row[$i]["title"] . '</div>');
	writeln('			</a>');
	writeln('		</td>');
	writeln('	</tr>');
}
end_tab();
right_box('<a class="icon_16 plus_16" href="add">Add Item</a>');

end_main();
print_footer();
