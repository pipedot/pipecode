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


function lang_string($hash)
{
	$lang_string = db_find_rec("lang_string", $hash);
	if ($lang_string === false) {
		return "";
	}

	return $lang_string["body"];
}


function lang_name($lang)
{
	$a = ["en" => "English", "es" => "Spanish", "eo" => "Esperanto", "ja" => "Japanese"];
	if (array_key_exists($lang, $a)) {
		return $a[$lang];
	}

	return "Unknown";
}


function lang_detect($src_string)
{
	global $translate_key;
	global $translate_max;

	if ($src_string === "" || strlen($src_string) > $translate_max) {
		return "en";
	}

	$src_hash = crypt_sha256($src_string);

	$row = sql("select src_lang from lang_translation where src_hash = ? order by src_lang", $src_hash);
	if (count($row) > 0) {
		//writeln("cache hit [$src_string]");
		return $row[0]["src_lang"];
	}

	$value = urlencode($src_string);
	$url ="https://www.googleapis.com/language/translate/v2/detect?key=$translate_key&q=$value";
	$body = http_slurp($url);
	//var_dump($body);
	$json = json_decode($body);
	$src_lang = $json->data->detections[0][0]->language;
	if ($src_lang == "" || strlen($src_lang) > 2) {
		$src_lang = "en";
	}

	return $src_lang;
}


function translate($src_string, $dst_lang, $src_lang = "")
{
	global $translate_key;
	global $translate_max;

	if ($src_string === "") {
		return "";
	}
	if (strlen($src_string) > $translate_max) {
		return false;
	}
	if ($dst_lang == $src_lang) {
		return $src_string;
	}

	$src_hash = crypt_sha256($src_string);

//	if ($src_lang == "") {
//		$row = sql("select dst_hash from lang_translation where src_hash = ?", $src_hash);
//		if (count($row) == 1) {
//			$dst_hash = $row[0]["dst_hash"];
//			//$lang_string = db_get_rec("lang_string", $dst_hash);
//			writeln("cache hit src_lang [$src_lang] dst_lang [$dst_lang] src_hash [$src_hash] dst_hash [$dst_hash]");
//			return lang_string($dst_hash);
//		} else if (count($row) > 1) {
//			$
//		}
//	} else {
		$lang_translation = db_find_rec("lang_translation", ["src_hash" => $src_hash, "dst_lang" => $dst_lang]);
		if ($lang_translation !== false) {
			$dst_hash = $lang_translation["dst_hash"];
			$lang_string = db_get_rec("lang_string", $dst_hash);
			//writeln("cache hit src_lang [$src_lang] dst_lang [$dst_lang] src_hash [$src_hash] dst_hash [$dst_hash]");
			return $lang_string["body"];
			//return lang_string($dst_hash);
		}
//	}

	if (!db_has_rec("lang_string", $src_hash)) {
		$lang_string = db_new_rec("lang_string");
		$lang_string["hash"] = $src_hash;
		$lang_string["body"] = $src_string;
		db_set_rec("lang_string", $lang_string);
	}

	$value = urlencode($src_string);
	if ($src_lang == "") {
		$url ="https://www.googleapis.com/language/translate/v2?key=$translate_key&q=$value&target=$dst_lang";
	} else {
		$url ="https://www.googleapis.com/language/translate/v2?key=$translate_key&q=$value&source=$src_lang&target=$dst_lang";
	}
	$body = http_slurp($url);
	//var_dump($body);

	$json = json_decode($body);
	$dst_string = @$json->data->translations[0]->translatedText;
	if ($dst_string == "") {
		writeln("failed translation request url [$url] body [$body]");
		return false;
	}
	$dst_hash = crypt_sha256($dst_string);
	if ($src_lang == "") {
		$src_lang = $json->data->translations[0]->detectedSourceLanguage;
	}

	if (!db_has_rec("lang_string", $dst_hash)) {
		$lang_string = db_new_rec("lang_string");
		$lang_string["hash"] = $dst_hash;
		$lang_string["body"] = $dst_string;
		db_set_rec("lang_string", $lang_string);
	}

	$lang_translation = db_new_rec("lang_translation");
	$lang_translation["src_hash"] = $src_hash;
	$lang_translation["src_lang"] = $src_lang;
	$lang_translation["dst_hash"] = $dst_hash;
	$lang_translation["dst_lang"] = $dst_lang;
	db_set_rec("lang_translation", $lang_translation);

	return $dst_string;
}

