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

include("$doc_root/lib/htmlpurifier/HTMLPurifier.standalone.php");


function mb_ord($u) {
	$k = mb_convert_encoding($u, 'UCS-4LE', 'UTF-8');
	$k1 = ord(substr($k, 0, 1));
	$k2 = ord(substr($k, 1, 1));
	$k3 = ord(substr($k, 2, 1));
	$k4 = ord(substr($k, 3, 1));

	return $k4 * 16777216 + $k3 * 65536 + $k2 * 256 + $k1;
}


function clean_character($c)
{
	$n = mb_ord($c);

	// Basic Latin
	if ($n >= 32 && $n <= 126) {
		return true;
	}

	// Latin-1 Supplement
	if ($n >= 161 && $n <= 255 && $n != 173) {
		return true;
	}

	// Latin Extended-A
	if ($n >= 256 && $n <= 383) {
		return true;
	}

	// Latin Extended-B
	if ($n >= 384 && $n <= 591) {
		return true;
	}

	// IPA Extentions
	if ($n >= 592 && $n <= 687) {
		return true;
	}

	// Greek and Coptic
	if ($n >= 880 && $n <= 1023 && $n != 888 && $n != 889 && $n != 895 && $n != 896 && $n != 897 && $n != 898 && $n != 899 && $n != 907 && $n != 909 && $n != 930) {
		return true;
	}

	// Cyrillic
	if ($n >= 1024 && $n <= 1279) {
		return true;
	}

	// Cyrillic Supplement
	if ($n >= 1280 && $n <= 1319 && $n != 1310 && $n != 1311) {
		return true;
	}

	// Phonetic Extensions
	if ($n >= 7424 && $n <= 7551) {
		return true;
	}

	// Phonetic Extensions Supplement
	if ($n >= 7552 && $n <= 7615) {
		return true;
	}

	// Latin Extended Additional
	if ($n >= 7680 && $n <= 7935) {
		return true;
	}

	// General Punctuation
	if (($n >= 8208 && $n <= 8231) || ($n >= 8240 && $n <= 8286)) {
		return true;
	}

	// Superscripts and Subscripts
	if ($n >= 8304 && $n <= 8348 && $n != 8306 && $n != 8307) {
		return true;
	}

	// Currency Symbols
	if ($n >= 8352 && $n <= 8378) {
		return true;
	}

	// Letterlike Symbols
	if ($n >= 8448 && $n <= 8527) {
		return true;
	}

	// Number Forms
	if ($n >= 8528 && $n <= 8581 || $n == 8585) {
		return true;
	}

	// Mathematical Operators
	if ($n >= 8704 && $n <= 8959) {
		return true;
	}

	// Supplemental Mathematical Operators
	if ($n >= 10752 && $n <= 11007) {
		return true;
	}

	// Latin Extended-C
	if ($n >= 11360 && $n <= 11391 && $n != 11384) {
		return true;
	}

	// CJK Symbols and Punctuation
	if ($n >= 12289 && $n <= 12351) {
		return true;
	}

	// Hiragana
	if ($n >= 12353 && $n <= 12447 && $n != 12439 && $n != 12440) {
		return true;
	}

	// Katakana
	if ($n >= 12448 && $n <= 12543) {
		return true;
	}

	// Bopomofo
	if ($n >= 12549 && $n <= 12585) {
		return true;
	}

	// Katakana Phonetic Extensions
	if ($n >= 12784 && $n <= 12799) {
		return true;
	}

	// CJK Unified Ideographs Extension A
	if ($n >= 13312 && $n <= 19893) {
		return true;
	}

	// CJK Unified Ideographs
	if ($n >= 19968 && $n <= 40899) {
		return true;
	}

	return false;
}


function clean_unicode($dirty)
{
	mb_internal_encoding("UTF-8");
	$s = "";

	for ($i = 0; $i < mb_strlen($dirty); $i++) {
		$c = mb_substr($dirty, $i, 1);

		if (clean_character($c)) {
			$s .= $c;
		}
	}

	return $s;
}


function clean_html($dirty, $definition = "comment")
{
	global $server_name;

	$dirty = clean_unicode($dirty);
	$dirty = str_replace("&nbsp;", " ", $dirty);

	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.DefinitionID', $definition);
	$config->set('HTML.DefinitionRev', 1);
	$config->set('Cache.DefinitionImpl', null); // TODO: remove this later!
	if ($definition == "page") {
		$config->set('HTML.Allowed', 'a[href],b,br,blockquote,i,img[src],li,ol,pre,q,s,sub,sup,u,ul,p,table,tr,td');
	} else if ($definition == "journal") {
		$config->set('HTML.Allowed', 'a[href],b,br,blockquote,i,img[src],li,ol,pre,q,s,sub,sup,u,ul');
	} else if ($definition == "article") {
		$config->set('HTML.Allowed', 'a[href],b,br,blockquote,i,img[src],li,ol,pre,q,s,sub,sup,u,ul,p,table,tr,th,td');
	} else if ($definition == "text") {
		$config->set('HTML.Allowed', '');
	} else {
		$config->set('HTML.Allowed', 'a[href],b,br,blockquote,i,li,ol,q,s,sub,sup,u,ul');
	}
	if ($definition == "text") {
		$config->set('Core.HiddenElements', array("sub" => true, "sup" => true));
	} else {
		if ($definition != "story") {
			$config->set('HTML.Nofollow', true);
		}
		$config->set('Output.SortAttr', true);
		$config->set('AutoFormat.Linkify', true);
	}

	// FIXME: this should match subdomains, but doesn't seem to work
	// Ideally, subdomains of $server_name wouldn't get nofollow links
	$config->set('URI.Host', $server_name);
	//$config->set('URI.Base', $server_name);
	//$config->set('URI.MakeAbsolute', true);

	$purifier = new HTMLPurifier($config);
	$clean = $purifier->purify($dirty);

	$clean = str_replace("<br />", "<br>", $clean);
	$clean = str_replace("<br/>", "<br>", $clean);
	$clean = clean_newlines("pre", $clean);
	$clean = clean_newlines("ol", $clean);
	$clean = clean_newlines("ul", $clean);
	$clean = clean_newlines("li", $clean);
	$clean = clean_newlines("blockquote", $clean);

	$clean = clean_spaces("b", $clean);
	$clean = clean_spaces("i", $clean);
	$clean = clean_spaces("u", $clean);
	$clean = clean_spaces("s", $clean);
	$clean = clean_spaces("q", $clean);
	$clean = clean_spaces("p", $clean);
	$clean = clean_spaces("li", $clean);
	$clean = clean_spaces("td", $clean);
	$clean = clean_spaces("blockquote", $clean);

	$clean = str_replace("<li></li>", "", $clean);
	$clean = clean_entities($clean);
	$clean = string_replace_all("  ", " ", $clean);
	$clean = trim($clean);

	while (substr($clean, 0, 4) == "<br>") {
		$clean = trim(substr($clean, 5));
	}
	while (substr($clean, -4, 4) == "<br>") {
		$clean = trim(substr($clean, 0, -5));
	}
	$clean = string_replace_all("<br><br><br>", "<br><br>", $clean);

	return $clean;
}


function dirty_html($clean)
{
	$dirty = str_replace("<br>", "\n", $clean);
	$dirty = str_replace("<blockquote>", "\n<blockquote>", $dirty);
	$dirty = str_replace("</blockquote>", "</blockquote>\n", $dirty);
	$dirty = str_replace("<ol>", "\n<ol>", $dirty);
	$dirty = str_replace("</ol>", "\n</ol>\n", $dirty);
	$dirty = str_replace("<ul>", "\n<ul>", $dirty);
	$dirty = str_replace("</ul>", "\n</ul>\n", $dirty);
	$dirty = str_replace("<li>", "\n<li>", $dirty);
	$dirty = str_replace("&lt;", "&amp;lt;", $dirty);
	$dirty = str_replace("&gt;", "&amp;gt;", $dirty);

	$dirty = str_replace("</blockquote>\n\n", "</blockquote>\n", $dirty);
	$dirty = str_replace("</ol>\n\n", "</ol>\n", $dirty);
	$dirty = str_replace("</ul>\n\n", "</ul>\n", $dirty);

	return $dirty;
}


function clean_newlines($tag, $text)
{
	$beg_tag = "<$tag>";
	$end_tag = "</$tag>";

	$text = string_replace_all("$beg_tag<br>", $beg_tag, $text);
	$text = string_replace_all("<br>$beg_tag", $beg_tag, $text);
	$text = string_replace_all("$end_tag<br>", $end_tag, $text);
	$text = string_replace_all("<br>$end_tag", $end_tag, $text);

	return $text;
}


function clean_spaces($tag, $text)
{
	$beg_tag = "<$tag>";
	$end_tag = "</$tag>";

	$text = string_replace_all("$beg_tag ", $beg_tag, $text);
	$text = string_replace_all(" $end_tag", $end_tag, $text);

	return $text;
}


function clean_entities($dirty)
{
	$a = array();

	// math
	$a[] = "forall";
	$a[] = "part";
	$a[] = "exist";
	$a[] = "empty";
	$a[] = "nabla";
	$a[] = "isin";
	$a[] = "notin";
	$a[] = "ni";
	$a[] = "prod";
	$a[] = "sub";
	$a[] = "minus";
	$a[] = "lowast";
	$a[] = "radic";
	$a[] = "prop";
	$a[] = "infin";
	$a[] = "ang";
	$a[] = "and";
	$a[] = "or";
	$a[] = "cap";
	$a[] = "cup";
	$a[] = "int";
	$a[] = "there4";
	$a[] = "sim";
	$a[] = "cong";
	$a[] = "asymp";
	$a[] = "ne";
	$a[] = "equiv";
	$a[] = "le";
	$a[] = "ge";
	$a[] = "sub";
	$a[] = "sup";
	$a[] = "nsub";
	$a[] = "sube";
	$a[] = "supe";
	$a[] = "oplus";
	$a[] = "otimes";
	$a[] = "perp";
	$a[] = "plusmn";
	$a[] = "frac14";
	$a[] = "frac12";
	$a[] = "frac34";
	$a[] = "divide";

	// greek
	$a[] = "Alpha";
	$a[] = "Beta";
	$a[] = "Gamma";
	$a[] = "Delta";
	$a[] = "Epsilon";
	$a[] = "Zeta";
	$a[] = "Eta";
	$a[] = "Theta";
	$a[] = "Iota";
	$a[] = "Kappa";
	$a[] = "Lambda";
	$a[] = "Mu";
	$a[] = "Nu";
	$a[] = "Xi";
	$a[] = "Omicron";
	$a[] = "Pi";
	$a[] = "Rho";
	$a[] = "Sigma";
	$a[] = "Tau";
	$a[] = "Upsilon";
	$a[] = "Phi";
	$a[] = "Chi";
	$a[] = "Psi";
	$a[] = "Omega";
	$a[] = "alpha";
	$a[] = "beta";
	$a[] = "gamma";
	$a[] = "delta";
	$a[] = "epsilon";
	$a[] = "zeta";
	$a[] = "eta";
	$a[] = "theta";
	$a[] = "iota";
	$a[] = "kappa";
	$a[] = "lambda";
	$a[] = "mu";
	$a[] = "nu";
	$a[] = "xi";
	$a[] = "omnicron";
	$a[] = "pi";
	$a[] = "rho";
	$a[] = "sigmaf";
	$a[] = "sigma";
	$a[] = "tau";
	$a[] = "upsilon";
	$a[] = "phi";
	$a[] = "chi";
	$a[] = "psi";
	$a[] = "omega";
	$a[] = "thetasym";
	$a[] = "upsih";
	$a[] = "straightphi";
	$a[] = "piv";
	$a[] = "Gammad";
	$a[] = "gammad";
	$a[] = "varkappa";
	$a[] = "varrho";
	$a[] = "straightepsilon";
	$a[] = "backepsilon";

	// latin
	$a[] = "Agrave";
	$a[] = "Aacute";
	$a[] = "Acirc";
	$a[] = "Atilde";
	$a[] = "Auml";
	$a[] = "Aring";
	$a[] = "AElig";
	$a[] = "Ccedil";
	$a[] = "Egrave";
	$a[] = "Eacute";
	$a[] = "Ecirc";
	$a[] = "Euml";
	$a[] = "Igrave";
	$a[] = "Iacute";
	$a[] = "Icirc";
	$a[] = "Iuml";
	$a[] = "ETH";
	$a[] = "Ntilde";
	$a[] = "Ograve";
	$a[] = "Oacute";
	$a[] = "Ocirc";
	$a[] = "Otilde";
	$a[] = "Ouml";
	$a[] = "times";
	$a[] = "Oslash";
	$a[] = "Ugrave";
	$a[] = "Uacute";
	$a[] = "Ucirc";
	$a[] = "Uuml";
	$a[] = "Yacute";
	$a[] = "THORN";
	$a[] = "szlig";
	$a[] = "agrave";
	$a[] = "aacute";
	$a[] = "acirc";
	$a[] = "atilde";
	$a[] = "auml";
	$a[] = "aring";
	$a[] = "aelig";
	$a[] = "ccedil";
	$a[] = "egrave";
	$a[] = "eacute";
	$a[] = "ecirc";
	$a[] = "euml";
	$a[] = "igrave";
	$a[] = "iacute";
	$a[] = "icirc";
	$a[] = "iuml";
	$a[] = "eth";
	$a[] = "ntilde";
	$a[] = "ograve";
	$a[] = "oacute";
	$a[] = "ocirc";
	$a[] = "otilde";
	$a[] = "ouml";
	$a[] = "oslash";
	$a[] = "ugrave";
	$a[] = "uacute";
	$a[] = "ucirc";
	$a[] = "uuml";
	$a[] = "yacute";
	$a[] = "thorn";
	$a[] = "yuml";
	$a[] = "OElig";
	$a[] = "oelig";
	$a[] = "Scaron";
	$a[] = "scaron";
	$a[] = "Yuml";
	$a[] = "fnof";
	$a[] = "circ";
	$a[] = "tilde";
	$a[] = "Alpha";

	// currency
	$a[] = "euro";
	$a[] = "cent";
	$a[] = "pound";
	$a[] = "yen";
	$a[] = "curren";

	// other
	$a[] = "copy";
	$a[] = "reg";
	$a[] = "trade";
	$a[] = "sup1";
	$a[] = "sup2";
	$a[] = "sup3";

	// symbols
	$a[] = "deg";
	$a[] = "micro";
	$a[] = "para";
	$a[] = "middot";
	$a[] = "dagger";
	$a[] = "Dagger";
	$a[] = "bull";
	$a[] = "hellip";
	$a[] = "permil";
	$a[] = "prime";
	$a[] = "Prime";

	// punctuation
	$a[] = "quot";
	$a[] = "amp";
	$a[] = "apos";
	$a[] = "lt";
	$a[] = "gt";
	$a[] = "nbsp";
	$a[] = "iexcl";
	$a[] = "brvbar";
	$a[] = "sect";
	$a[] = "ordf";
	$a[] = "iquest";
	$a[] = "sdot";
	$a[] = "vellip";

	// quotes
	$a[] = "laquo";
	$a[] = "raquo";
	$a[] = "lsquo";
	$a[] = "rsquo";
	$a[] = "sbquo";
	$a[] = "ldquo";
	$a[] = "rdquo";
	$a[] = "bdquo";
	$a[] = "lsaquo";
	$a[] = "rsaquo";
	$a[] = "lceil";
	$a[] = "rceil";
	$a[] = "lfloor";
	$a[] = "rfloor";
	$a[] = "lang";
	$a[] = "rang";

	$len = mb_strlen($dirty);
	$inside = false;
	$s = "";
	$t = "";

	for ($i = 0; $i < $len; $i++) {
		$c = mb_substr($dirty, $i, 1);
		if ($inside) {
			if ($c == "&") {
				// nested ampersand
				$s .= "&" . $t;
				$t = "";
			} else if ($c == ";") {
				$inside = false;
				if (@mb_substr($t, 0, 1) == "#") {
					// numerical entity - nuke it
					//writeln("numerical");
				} else if (in_array($t, $a)) {
					// valid entity
					//writeln("valid");
					$s .= "&" . $t . ";";
				} else {
					// invalid entity
					//writeln("invalid");
				}
			} else if (!string_uses($c, "[A-Z][a-z][0-9]#")) {
				// dangling entity
				//writeln("dangling [$c] [$i]");
				$s .= "&" . $t . $c;
				$inside = false;
			} else {
				$t .= $c;
			}
		} else {
			if ($c == "&") {
				$t = "";
				$inside = true;
			} else {
				$s .= $c;
			}
		}
	}

	return $s;
}


function clean_text($text, $max = 200, $min = 1)
{
	$text = htmlspecialchars($text);
	$text = clean_unicode($text);
	$text = clean_entities($text);
	$text = string_replace_all("  ", " ", trim($text));

	if (strlen($text) > $max) {
		$subject = substr($text, 0, $max);
	}
	if (strlen($text) < $min) {
		die("missing text field");
	}

	return $text;
}


function clean_subject()
{
	if (array_key_exists("subject", $_POST)) {
		$subject = $_POST["subject"];
	} else if (array_key_exists("title", $_POST)) {
		$subject = $_POST["title"];
	} else {
		die("no subject");
	}

	return clean_text($subject);
}


function clean_topic()
{
	if (array_key_exists("topic", $_POST)) {
		$topic = strtolower($_POST["topic"]);
	} else {
		$topic = "general";
	}

	$topic = string_clean($topic, "[a-z][0-9]-");
	$topic = clean_url($topic);

	if (strlen($topic) > 20) {
		$topic = substr($topic, 0, 20);
	}

	return $topic;
}


function clean_slug()
{
	$slug = http_post_string("slug", array("required" => false, "len" => 100, "valid" => "[a-z][A-Z][0-9]-_."));
	if ($slug == "") {
		$slug = http_get_string("slug", array("required" => false, "len" => 100, "valid" => "[a-z][A-Z][0-9]-_."));
	}
	if ($slug == "") {
		$slug = clean_url(clean_subject());
	}

	return $slug;
}


function clean_body($required = true, $definition = "comment")
{
	global $auth_user;

	if (array_key_exists("body", $_POST)) {
		$dirty_body = $_POST["body"];
	} else if (array_key_exists("comment", $_POST)) {
		$dirty_body = $_POST["comment"];
	} else if (array_key_exists("story", $_POST)) {
		$dirty_body = $_POST["story"];
	} else {
		if ($required) {
			die("no body");
		} else {
			return array("", "");
		}
	}

	// XXX: ugly hack while submit/publish story is not wysiwyg
	if ($auth_user["javascript_enabled"] && $auth_user["wysiwyg_enabled"] && !array_key_exists("tid", $_POST)) {
		$clean_body = $dirty_body;
	} else {
		$clean_body = str_replace("\n", "<br>", $dirty_body);
	}
	$clean_body = clean_html($clean_body, $definition);
	$dirty_body = dirty_html($clean_body);
	// XXX: ugly hack while submit/publish story is not wysiwyg
	if ($auth_user["javascript_enabled"] && $auth_user["wysiwyg_enabled"] && !array_key_exists("tid", $_POST)) {
		$dirty_body = $clean_body;
	}

	if (strlen($clean_body) > 16384) {
		$clean_body = substr($clean_body, 0, 16384);
		$clean_body = clean_html($clean_body, $definition);
	}
	if (strlen($clean_body) == 0) {
		if ($required) {
			die("no body");
		} else {
			return array("", "");
		}
	}

	return array($clean_body, $dirty_body);
}


function clean_link()
{
	if (!array_key_exists("link", $_POST)) {
		return "";
	}

	$link = $_POST["link"];
	$link = string_clean($link, "[a-z][A-Z][0-9]~#%&()-_+=[];:./?");

	return $link;
}


function clean_tags()
{
	if (!array_key_exists("tags", $_POST)) {
		return array();
	}

	$tags = $_POST["tags"];
	$tags = strtolower($tags);
	$tags = str_replace(",", " ", $tags);
	$tags = string_clean($tags, "[a-z][0-9] ");
	$tags = explode(" ", $tags);

	if (array_key_exists("body", $_POST)) {
		$body = $_POST["body"];
		$body = strip_tags($body);
		$body = strtolower($body);
		$body = string_clean($body, "[a-z][0-9]# ");
		$a = explode(" ", $body);
		for ($i = 0; $i < count($a); $i++) {
			$tag = $a[$i];
			if (substr($tag, 0, 1) == "#") {
				$tags[] = string_clean(substr($tag, 1), "[a-z][0-9]");
			}
		}
	}

	$a = array_unique($tags);
	$tags = array();
	for ($i = 0; $i < count($a); $i++) {
		if (strlen(string_clean($a[$i], "[a-z]")) > 0) {
			$tags[] = substr($a[$i], 0, 20);
		}
		if (count($tags) == 3) {
			return $tags;
		}
	}

	return $tags;
}


function make_description($body)
{
	$desc = $body;

	if (string_has($desc, "<br>")) {
		$desc = substr($desc, 0, strpos($desc, "<br>"));
	}
	if (string_has($desc, "<blockquote>")) {
		$desc = substr($desc, 0, strpos($desc, "<blockquote>"));
	}
	if (string_has($desc, "<pre>")) {
		$desc = substr($desc, 0, strpos($desc, "<pre>"));
	}
	if (string_has($desc, "<ul>")) {
		$desc = substr($desc, 0, strpos($desc, "<ul>"));
	}
	if (string_has($desc, "<ol>")) {
		$desc = substr($desc, 0, strpos($desc, "<ol>"));
	}

	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.DefinitionID', "description");
	$config->set('HTML.DefinitionRev', 1);
	$config->set('Cache.DefinitionImpl', null); // TODO: remove this later!
	$config->set('HTML.Allowed', '');
	$config->set('Core.HiddenElements', array("sub" => true, "sup" => true));

	$purifier = new HTMLPurifier($config);
	$desc = $purifier->purify($desc);

	return trim($desc);
}
