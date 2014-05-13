<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
//
// This file is part of Pipecode.
//
// Pipecode is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Pipecode is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Pipecode.  If not, see <http://www.gnu.org/licenses/>.
//

include("clean.php");

if (!$auth_user["admin"]) {
	die("not an admin");
}

if (http_post()) {
	$slug = http_post_string("slug", array("len" => 100, "valid" => "[a-z][A-Z][0-9]-_."));
	$title = http_post_string("title", array("len" => 100, "valid" => "[ALL]"));

	$title = clean_unicode($title);
	$title = clean_entities($title);

	if (db_has_rec("page", $slug)) {
		die("page already exists [$slug]");
	}

	$page = array();
	$page["slug"] = $slug;
	$page["title"] = $title;
	$page["body"] = "";

	db_set_rec("page", $page);
	header("Location: ./");
	die();
}

print_header("Add Page");

beg_form();

beg_tab("Add Page");
print_row(array("caption" => "Slug", "text_key" => "slug"));
print_row(array("caption" => "Title", "text_key" => "title"));
end_tab();

right_box("Add");
end_form();

print_footer();
