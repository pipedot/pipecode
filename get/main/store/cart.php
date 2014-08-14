<?

print_header("Cart");
beg_main();

$customer_id = 0;
$row = run_sql("select store_item.item_id, category_id, image_id, featured, name, title, features, price, shipping, description, quantity from store_item inner join store_cart on store_cart.item_id = store_item.item_id where customer_id = ?", array($customer_id));
writeln('<form method="post">');
beg_tab();
writeln('	<tr>');
writeln('		<th width="80%" colspan="2">Shopping Cart</th>');
writeln('		<th width="10%" style="text-align: center">Quantity</th>');
writeln('		<th width="10%" style="text-align: right">Price</th>');
writeln('	</tr>');
for ($i = 0; $i < count($row); $i++) {
	writeln('	<tr class="r' .$r . '">');
	writeln('		<td>');
	writeln('			<img alt="product image" class="store_image_40" src="/images/store/image/' . $row[$i]["image_id"] . '-40.jpg"/>');
	writeln('		</td>');
	writeln('		<td>');
	writeln('			<table cellspacing="0" cellpadding="4" width="100%">');
	writeln('				<tr>');
	writeln('					<td colspan="2" class="store_cart_title"><a href="view?item_id=' . $row[$i]["item_id"] . '">' . $row[$i]["title"] . '</a></td>');
	writeln('				</tr>');
	writeln('				<tr>');
	writeln('					<td valign="top" class="store_item_tag">Description:</td>');
	writeln('					<td>' . $row[$i]["description"] . '</td>');
	writeln('				</tr>');
	writeln('			</table>');
	writeln('		</td>');
	writeln('		<td style="text-align: center"><input type="text" name="item_' . $row[$i]["item_id"] . '" value="' . $row[$i]["quantity"] . '" style="width: 40px"/></td>');
	writeln('		<td class="store_cart_price">$' . $row[$i]["price"] . '</td>');
	writeln('	</tr>');

	$r = ($r ? 0 : 1);
}
end_tab();
writeln('<div class="right_box">');
writeln('<input type="submit" name="update" value="Update"/>');
writeln('<input type="submit" name="checkout" value="Check Out"/>');
writeln('</div>');
writeln('</form>');

end_main();
print_footer();
