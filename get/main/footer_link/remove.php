<?
//
// Pipecode - distributed social network
// Copyright (C) 2014-2015 Bryan Beicker <bryan@pipedot.org>
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Affero General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Affero General Public License for more details.
//
// You should have received a copy of the GNU Affero General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

require_admin();

if (!string_uses($s2, "[a-z][0-9]-", 100)) {
	fatal("Invalid slug");
}
$slug = $s2;

$footer_link = db_get_rec("footer_link", $slug);

$spinner[] = ["name" => "Footer Link", "link" => "/footer_link/"];
$spinner[] = ["name" => $footer_link["name"], "link" => "/footer_link/$slug"];
$spinner[] = ["name" => "Remove", "link" => "/footer_link/$slug/remove"];

print_header(["title" => "Remove Footer Link", "form" => true]);

writeln('<h1>' . get_text('Remove $1', [$footer_link["name"]]) . '</h1>');
writeln('<p>' . get_text('Are you sure you want to remove the [<b>$1</b>] link?', $footer_link["name"]) . '</p>');

box_left("Remove");

print_footer(["form" => true]);
