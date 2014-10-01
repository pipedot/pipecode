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

if (!$accounting_enabled) {
	die("accounting disabled");
}
if ($zid != $auth_zid) {
	die("not your page");
}

print_header("Account Balance");
print_left_bar("user", "account");
beg_main("cell");

writeln('<h1>Account Balance</h1>');

//$row = sql("select aa.credits from (select sum(amount) as credits from ledger as aa where to_zid = ?)", $zid);
//$row = sql("(select sum(amount) as credits from ledger where to_zid = ? as a) union (select sum(amount) as debits from ledger where from_zid = ? as b)", $zid, $zid);
//$row = sql("select sum(amount) as debits from ledger where from_zid = ?", $zid);
$row = sql("select sum(amount) as balance from accounting.posting where zid = ?", $zid);
//$credits = (int) $row[0]["credits"];
//$debits = (int) $row[0]["debits"];
$balance = (int) $row[0]["balance"];
writeln('<div class="icon_32 coins_32">$' . format_money($balance) . '</div>');
//writeln('<div class="icon_32 coins_32">' . format_money($row[0]["balance"]) . '</div>');


writeln('<h2>Log</h2>');

//print "credits [" . $credits . "] debits [$debits]";
//print "balance [" . $balance . "]";
$items_per_page = 100;
list($item_start, $page_footer) = page_footer("select count(*) as item_count from accounting.posting inner join accounting.journal on accounting.posting.journal_id = accounting.journal.journal_id where accounting.posting.zid = ?", $items_per_page, $zid);

$row = sql("select amount, item_id, period, time, type from accounting.posting inner join accounting.journal on accounting.posting.journal_id = accounting.journal.journal_id where accounting.posting.zid = ? order by posting_id desc limit $item_start, $items_per_page", $zid);
beg_tab();
writeln('	<tr>');
writeln('		<th>Time</th>');
writeln('		<th>Type</th>');
writeln('		<th class="right">Amount</th>');
writeln('	</tr>');
for ($i = 0; $i < count($row); $i++) {
	writeln('	<tr>');
	writeln('		<td>' . date("Y-m-d H:i", $row[$i]["time"]) . '</td>');
	writeln('		<td>' . $row[$i]["type"] . '</td>');
	writeln('		<td class="right">$' . format_money($row[$i]["amount"]) . '</td>');
	writeln('	</tr>');
}
end_tab();

writeln($page_footer);

//writeln('<div class="right">Balance: ' . format_money($balance) . '</div>');
//right_box('Balance: <b>' . format_money($balance) . '</b>');

end_main();
print_footer();
