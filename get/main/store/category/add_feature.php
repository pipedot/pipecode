<?

print_header("Add Feature");
beg_main();
beg_form();

beg_tab("Add Feature");
print_row(array("caption" => "Name", "text_key" => "feature_name"));
print_row(array("caption" => "Description", "textarea_key" => "feature_description"));
end_tab();

right_box("Save");

end_form();
end_main();
print_footer();
