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

function mb_ord($u) {
	$k = mb_convert_encoding($u, 'UCS-2LE', 'UTF-8');
	$k1 = ord(substr($k, 0, 1));
	$k2 = ord(substr($k, 1, 1));

	return $k2 * 256 + $k1;
}


function clean_character($c)
{
	$n = mb_ord($c);
	//writeln("c [$c] n [$n]");

	// Basic Latin
	if ($n >= 21 && $n <= 126) {
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


function clean_tag($tag)
{
	$clean = "";
	$tag = trim($tag);

	if (substr($tag, 0, 1) == "/") {
		$end = true;
		$tag = substr($tag, 1);
	} else {
		$end = false;
	}

	$type = string_next($tag, " ");
	//print "type [$type]\n";

	if ($type == "br") {
		return "<br/>";
	}
	if ($type == "b" || $type == "i" || $type == "u" || $type == "s" || $type == "q" || $type == "strong" || $type == "em") {
		if ($type == "strong") {
			$type = "b";
		} else if ($type == "em") {
			$type = "i";
		}
		if ($end) {
			return "</$type>FORCEWHITESPACE";
		} else {
			return "FORCEWHITESPACE<$type>";
		}
	}
	//if ($type == "p"  || $type == "ol" || $type == "ul" || $type == "li" || $type == "pre") {
	//if ($type == "pre") {
	if ($type == "ol" || $type == "ul" || $type == "li" || $type == "pre" || $type == "blockquote") {
		if ($end) {
			return "</$type>";
		} else {
			return "<$type>";
		}
	}
	if ($type == "a") {
		if ($end) {
			if ($type == "a") {
				return "</a>FORCEWHITESPACE";
			} else {
				return "</$type>";
			}
		}
		$tag = str_replace(" ", "", $tag);
		$tag = str_replace("\"", "\" ", $tag);
		$tag = str_replace("=\" ", "=\"", $tag);
		$tag = trim($tag);
		$map_old = map_from_tag_string($tag);
		$map_new = array();
		if ($type == "a") {
			$map_new["href"] = @$map_old["href"];
		}
		if (count($map_new) == 0) {
			return "<$type>";
		} else {
			if ($type == "a") {
				return "FORCEWHITESPACE<$type " . map_to_tag_string($map_new) . ">";
			} else {
				return "<$type " . map_to_tag_string($map_new) . ">";
			}
		}
	}

	return "";
}


function clean_html($html)
{
	$clean = "";
	$pre = 0;

	$html = clean_unicode($html);
	$html = str_replace("<br />", "<br/>", $html);
	$html = str_replace("&nbsp;", " ", $html);

	for ($i = 0; $i < mb_strlen($html); $i++) {
		//$c = substr($html, $i, 1);
		$c = mb_substr($html, $i, 1);
		if ($c == "<") {
			$s = "";
			for ($i = $i + 1; $i < mb_strlen($html); $i++) {
				//$c = substr($html, $i, 1);
				$c = mb_substr($html, $i, 1);
				if ($c == ">") {
					break;
				}
				$s .= $c;
			}
			$tag = clean_tag($s);
			if ($tag == "<pre>") {
				$pre++;
			} else if ($tag == "</pre>") {
				$pre--;
			}
			$clean .= $tag;
		} else {
			//if ($pre > 0 && $c == "\n") {
			//	$clean .= "<br/>";
			//} else {
				$clean .= $c;
			//}
		}
	}

	$clean = str_replace("\t", " ", $clean);
	$clean = str_replace("\n", " ", $clean);
	$clean = str_replace("\r", " ", $clean);

	while (string_has($clean, "  ")) {
		$clean = str_replace("  ", " ", $clean);
	}

	$clean = str_replace("> ", ">", $clean);
	$clean = str_replace(" <", "<", $clean);
	$clean = str_replace("FORCEWHITESPACE", " ", $clean);
	$clean = trim($clean);
	$clean = str_replace_all("  ", " ", $clean);
	$clean = str_replace_all("<br/><br/><br/>", "<br/><br/>", $clean);

//	print "clean [$clean]";
//	$clean = str_replace("<pre><br/>", "<pre>", $clean);
//	$clean = str_replace("<br/></pre>", "</pre>", $clean);
//	$clean = str_replace("<li><br/>", "<li>", $clean);
//	$clean = str_replace("<br/></li>", "</li>", $clean);
//	$clean = str_replace("<ul><br/>", "<ul>", $clean);
//	$clean = str_replace("<br/></ul>", "</ul>", $clean);
//	$clean = str_replace("<ol><br/>", "<ol>", $clean);
//	$clean = str_replace("<br/></ol>", "</ol>", $clean);
//	print "clean2 [$clean]";
	$clean = clean_newlines("pre", $clean);
	$clean = clean_newlines("ol", $clean);
	$clean = clean_newlines("ul", $clean);
	$clean = clean_newlines("li", $clean);
	$clean = clean_newlines("blockquote", $clean);

	$clean = clean_entities($clean);
	$clean = make_clickable($clean);

	return $clean;
}


function dirty_html($clean)
{
	$dirty = str_replace("<br/>", "\n", $clean);
	$dirty = str_replace("<blockquote>", "\n<blockquote>", $dirty);
	$dirty = str_replace("</blockquote>", "</blockquote>\n", $dirty);
	$dirty = str_replace("<ol>", "\n<ol>", $dirty);
	$dirty = str_replace("</ol>", "\n</ol>", $dirty);
	$dirty = str_replace("<ul>", "\n<ul>", $dirty);
	$dirty = str_replace("</ul>", "\n</ul>", $dirty);
	$dirty = str_replace("<li>", "\n<li>", $dirty);
	$dirty = str_replace("&lt;", "&amp;lt;", $dirty);
	$dirty = str_replace("&gt;", "&amp;gt;", $dirty);

	return $dirty;
}


function clean_newlines($tag, $text)
{
	$beg_tag = "<$tag>";
	$end_tag = "</$tag>";

	$text = str_replace_all("$beg_tag<br/>", $beg_tag, $text);
	$text = str_replace_all("<br/>$beg_tag", $beg_tag, $text);
	$text = str_replace_all("$end_tag<br/>", $end_tag, $text);
	$text = str_replace_all("<br/>$end_tag", $end_tag, $text);

	return $text;
}


function make_clickable($text)
{
	$text = preg_replace("/(?<!a href=\")(?<!src=\")((http|ftp)+(s)?:\/\/[^<>\s]+)/i", "<a href=\"\\0\">\\0</a>", $text);
	$text = preg_replace( '#(<a([ \r\n\t]+[^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i', "$1$3</a>", $text);

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
