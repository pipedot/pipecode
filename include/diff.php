<?
//
// Pipecode - distributed social network
// Copyright (C) 2014-2016 Bryan Beicker <bryan@pipedot.org>
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

include("$doc_root/lib/finediff/finediff.php");


function diff($old_text, $new_text)
{
	$old_text = mb_convert_encoding($old_text, 'HTML-ENTITIES', 'UTF-8');
	$new_text = mb_convert_encoding($new_text, 'HTML-ENTITIES', 'UTF-8');

	$opcodes = FineDiff::getDiffOpcodes($old_text, $new_text);
	$diff = FineDiff::renderDiffToHTMLFromOpcodes($old_text, $opcodes);
	$diff = mb_convert_encoding($diff, 'UTF-8', 'HTML-ENTITIES');
	$diff = str_replace("&lt;", "<", $diff);
	$diff = str_replace("&gt;", ">", $diff);

	return $diff;
}
