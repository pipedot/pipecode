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
if ($auth_zid === "") {
	die("sign in to donate");
}
if ($zid !== $auth_zid) {
	die("not your page");
}

$amount = http_post_int("amount");
$type = "deposit";

db_begin();

$journal = db_new_rec("accounting.journal");
$journal["type"] = $type;
$journal["zid"] = $auth_zid;
db_set_rec("accounting.journal", $journal);
$journal_id = db_last();

$posting = db_new_rec("accounting.posting");
$posting["amount"] = $amount;
$posting["journal_id"] = $journal_id;
$posting["zid"] = $zid;
db_set_rec("accounting.posting", $posting);

$posting = db_new_rec("accounting.posting");
$posting["amount"] = $amount * -1;
$posting["journal_id"] = $journal_id;
$posting["zid"] = $server_zid;
db_set_rec("accounting.posting", $posting);

db_commit();

die("done");
header("Location: /account/balance");

