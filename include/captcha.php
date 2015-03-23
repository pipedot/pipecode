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

function get_captcha()
{
	global $captcha_key;

	if (empty($captcha_key)) {
		return get_captcha_fallback();
	}

	$url = "http://api.textcaptcha.com/$captcha_key";
	$xml = http_slurp($url);

	try {
		$xml = @new SimpleXMLElement($xml);
	} catch (Exception $e) {
		return get_captcha_fallback();
	}

	$question = (string) $xml->question;
	$question = str_replace("colour", "color", $question);

	$answer = "";
	foreach ($xml->answer as $hash) {
		$answer .= "$hash ";
	}
	$answer = trim($answer);

	$row = sql("select captcha_id from captcha where question = ?", $question);
	if (count($row) == 0) {
		sql("insert into captcha (question, answer) values (?, ?)", $question, $answer);
		$row = sql("select captcha_id from captcha where question = ?", $question);
	}

	return array($row[0]["captcha_id"], $question, $answer);
}


function get_captcha_fallback()
{
	$row = sql("select min(captcha_id) as min_id, max(captcha_id) as max_id from captcha");
	$min_id = $row[0]["min_id"];
	$max_id = $row[0]["max_id"];
	$captcha_id = rand($min_id, $max_id);
	$row = sql("select captcha_id, question, answer from captcha where captcha_id = ?", $captcha_id);

	return array($row[0]["captcha_id"], $row[0]["question"], $row[0]["answer"]);
}


function captcha_challenge()
{
	global $remote_ip;

	list($captcha_id, $question) = get_captcha();

	$captcha_challenge = array();
	$captcha_challenge["remote_ip"] = $remote_ip;
	$captcha_challenge["captcha_id"] = $captcha_id;
	db_set_rec("captcha_challenge", $captcha_challenge);

	return $question;
}


function captcha_verify($answer)
{
	global $remote_ip;

	$answer = crypt_md5(strtolower(trim($answer)));
	$captcha_challenge = db_get_rec("captcha_challenge", $remote_ip);
	$captcha = db_get_rec("captcha", $captcha_challenge["captcha_id"]);
	$a = explode(" ", $captcha["answer"]);

	return in_array($answer, $a);
}

?>
