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

//set_time_limit(14 * MINUTES);
header_text();
header_expires();

$langs = ["en", "eo", "es", "ja"];

for ($n = 0; $n < count($langs); $n++) {
	$dst_lang = $langs[$n];

	$row = sql("select * from story order by story_id desc");
	for ($i = 0; $i < count($row); $i++) {
		$body = $row[$i]["body"];
		$body_hash = crypt_sha256($body);
		$src_lang = $row[$i]["lang"];

		if ($src_lang != $dst_lang && strlen($body) < 5000) {
			if (!db_has_rec("lang_translation", ["src_hash" => $body_hash, "dst_lang" => $dst_lang])) {
				$body = translate($body, $dst_lang, $src_lang);

				if ($body == "") {
					die("error: translation blank [" . $row[$i]["story_id"] . "]");
				}

				writeln("story_id [" . $row[$i]["story_id"] . "]");
				writeln("original body [" . $row[$i]["body"] . "]");
				writeln("lang [$dst_lang] body [$body]");
				writeln();
				sleep(7);
			}
		}
	}

	$row = sql("select * from comment where junk_status = 0 order by comment_id desc");
	for ($i = 0; $i < count($row); $i++) {
		$src_lang = $row[$i]["lang"];
		if ($src_lang != $dst_lang) {
			$subject = $row[$i]["subject"];
			$body = $row[$i]["body"];

			$subject_hash = crypt_sha256($subject);
			$body_hash = crypt_sha256($body);

			if ($src_lang != $dst_lang && strlen($body) < 5000) {
				if (!db_has_rec("lang_translation", ["src_hash" => $subject_hash, "dst_lang" => $dst_lang]) || !db_has_rec("lang_translation", ["src_hash" => $body_hash, "dst_lang" => $dst_lang])) {
					$subject = translate($subject, $dst_lang, $src_lang);
					$body = translate($body, $dst_lang, $src_lang);

					if ($body == "") {
						die("error: translation blank [" . $row[$i]["comment_id"] . "]");
					}

					writeln("comment_id [" . $row[$i]["comment_id"] . "]");
					writeln("original subject [" . $row[$i]["subject"] . "] original body [" . $row[$i]["body"] . "]");
					writeln("lang [$dst_lang] subject [$subject] body [$body]");
					writeln();
					sleep(5);
				}
			}
		}
	}
}

print "done\n";

