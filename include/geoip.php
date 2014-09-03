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

include("$doc_root/lib/geoip/geoipcity.inc");
include("$doc_root/lib/geoip/timezone.php");


function geo_ip($ip)
{
	global $doc_root;
	global $gi_v4;
	global $gi_v6;
	global $GEOIP_REGION_NAME;

	if (string_has($ip, ":")) {
		if (empty($gi_v6)) {
			$gi_v6 = geoip_open("$doc_root/lib/geoip/GeoLiteCityv6.dat", GEOIP_STANDARD);
		}
		$record = geoip_record_by_addr_v6($gi_v6, $ip);
	} else {
		if (empty($gi_v4)) {
			$gi_v4 = geoip_open("$doc_root/lib/geoip/GeoLiteCity.dat", GEOIP_STANDARD);
		}
		$record = geoip_record_by_addr($gi_v4, $ip);
	}

	$a = array();
	if (empty($record->country_name)) {
		$a["country"] = "";
	} else {
		$a["country"] = $record->country_name;
	}
	if (empty($record->country_code)) {
		$a["country_code"] = "";
	} else {
		$a["country_code"] = $record->country_code;
	}
	if (empty($record->country_code) || empty($record->region)) {
		$a["region"] = "";
	} else {
		$a["region"] = $GEOIP_REGION_NAME[$record->country_code][$record->region];
	}
	if (empty($record->region)) {
		$a["region_code"] = "";
	} else {
		$a["region_code"] = $record->region;
	}
	if (empty($record->city)) {
		$a["city"] = "";
	} else {
		$a["city"] = $record->city;
	}
	if (empty($record->latitude)) {
		$a["latitude"] = "";
	} else {
		$a["latitude"] = $record->latitude;
	}
	if (empty($record->longitude)) {
		$a["longitude"] = "";
	} else {
		$a["longitude"] = $record->longitude;
	}
	if (empty($record->country_code) || empty($record->region)) {
		$a["timezone"] = "UTC";
	} else {
		$a["timezone"] = get_time_zone($record->country_code, $record->region);
	}

	return $a;
}


function geo_init()
{
	global $geo_im;
	global $geo_color;
	global $geo_width;
	global $geo_height;
	global $doc_root;

	$geo_im = imagecreatefrompng("$doc_root/www/images/map-1280.png");
	$geo_color = imagecolorallocate($geo_im, 255, 0, 0);

	$geo_width = imagesx($geo_im);
	$geo_height = imagesy($geo_im);
}


function geo_dot($lat, $lon)
{
	global $geo_width;
	global $geo_height;
	global $geo_im;
	global $geo_color;

	$x = (($lon + 180) * ($geo_width / 360));
	$y = ((($lat * -1) + 90) * ($geo_height / 180));
	imagefilledrectangle($geo_im, $x - 1, $y - 1, $x + 1, $y + 1, $geo_color);
}


function geo_plot()
{
	global $geo_im;

	header("Content-Type: image/png");
	imagepng($geo_im);
	imagedestroy($geo_im);
}
