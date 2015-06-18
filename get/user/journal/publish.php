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

require_mine();

$journal = item_request(TYPE_JOURNAL);

print_header("Publish", ["Write"], ["notepad"], ["/journal/write"], ["Journal", $journal["title"], "Publish"], ["/journal/", "/journal/" . $journal["short_code"], "/journal/" . $journal["short_code"] . "/publish"]);
beg_main();
beg_form();

writeln('<h1>Publish</h1>');
writeln('<p>Ready to publish the journal entry [<b>' . $journal["title"] . '</b>]?</p>');

box_left("Publish");

end_form();
end_main();
print_footer();


