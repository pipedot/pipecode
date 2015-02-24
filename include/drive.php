<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
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

const DRIVE_DIR = 0;
const DRIVE_FILE = 1;


function resolve_path($path, $zid)
{
	if ($path === "/") {
		return 0;
	}
	if (substr($path, 0, 1) !== "/") {
		die("invalid path [$path]");
	}
	$path = substr($path, 1);
	if (substr($path, -1) === "/") {
		$path = substr($path, 0, -1);
	}

	$parent_id = 0;
	$a = explode("/", $path);
	for ($i = 0; $i < count($a); $i++) {
		$row = sql("select file_id from drive_file where parent_id = ? and type = ? and zid = ? and name = ?", $parent_id, DRIVE_DIR, $zid, $a[$i]);
		if (count($row) == 0) {
			die("path not found [$path]");
		}
		$parent_id = $row[0]["file_id"];
		//writeln("found [" . $a[$i] . "] file_id [$parent_id]<br/>\n");
	}

	return $parent_id;
}


function print_drive_crumbs($path, $zid)
{
	//$crumbs = '<a class="icon_16 home_16" href="/drive/">Home</a>';
	$crumbs = '<li><a class="icon_16 home_16" href="/drive/"></a></li>';
	if ($path === "/") {
		//writeln('<div class="drive_crumbs">' . $crumbs . '</div>');
		writeln('<ul class="breadcrumbs">' . $crumbs . '</ul>');
		return;
	}
	if (substr($path, 0, 1) !== "/") {
		die("invalid path [$path]");
	}
	$path = substr($path, 1);
	if (substr($path, -1) === "/") {
		$path = substr($path, 0, -1);
	}

	$link = "/drive";
	$parent_id = 0;
	$a = explode("/", $path);
	for ($i = 0; $i < count($a); $i++) {
		$row = sql("select file_id from drive_file where parent_id = ? and type = ? and zid = ? and name = ?", $parent_id, DRIVE_DIR, $zid, $a[$i]);
		if (count($row) == 0) {
			die("path not found [$path]");
		}
		$parent_id = $row[0]["file_id"];
		$clean_name = encode_file_name($a[$i]);
		$link .= "/" . $clean_name;
		//$crumbs .= ' / <a href="' . $link . '">' . $clean_name . '</a>';
		$crumbs .= '<li><a href="' . $link . '">' . $clean_name . '</a></li>';
		//writeln("found [" . $a[$i] . "] file_id [$parent_id]<br/>\n");
	}

	//writeln('<div class="drive_crumbs">' . $crumbs . '</div>');
	writeln('<ul class="breadcrumbs">' . $crumbs . '</ul>');
}


function print_drive_folder($path, $zid)
{
	$path_id = resolve_path($path, $zid);

	beg_tab();
/*	$row = sql("select path_id, name, time from drive_path where parent_id = ? and zid = ?", $path_id, $zid);
	for ($i = 0; $i < count($row); $i++) {
		$clean_name = encode_file_name($row[$i]["name"]);

		writeln('	<tr>');
		writeln('		<td><a class="icon_16 folder_16" href="' . $clean_name . '/">' . $clean_name . '</a></td>');
		writeln('		<td class="center"></td>');
		writeln('		<td class="right nowrap">' . date("Y-m-d H:i", $row[$i]["time"]) . '</td>');
		writeln('	</tr>');
		$count++;
	}*/
	$row = sql("select file_id, name, time, type from drive_file where parent_id = ? and zid = ? order by type, name", $path_id, $zid);
	if (count($row) == 0) {
		writeln('	<tr>');
		writeln('		<td>(empty)</td>');
		writeln('	</tr>');
	}
	for ($i = 0; $i < count($row); $i++) {
		$clean_name = encode_file_name($row[$i]["name"]);
		if ($row[$i]["type"] == DRIVE_DIR) {
			$icon = "folder_16";
			$link = "$clean_name/";
		} else {
			$icon = file_icon(fs_ext($clean_name));
			$link = $clean_name;
		}

		writeln('	<tr>');
		writeln('		<td><a class="icon_16 ' . $icon . '" href="' . $link . '">' . $clean_name . '</a></td>');
		writeln('		<td class="center"></td>');
		writeln('		<td class="right nowrap">' . date("Y-m-d H:i", $row[$i]["time"]) . '</td>');
		writeln('	</tr>');
	}
	end_tab();
}


function file_icon($ext)
{
	if ($ext == "txt") {
		return "notepad_16";
	} else if ($ext == "jpg") {
		return "picture_16";
	} else if ($ext == "png") {
		return "picture_16";
	} else {
		return "package_16";
	}
}


function encode_file_name($name)
{
	$name = urlencode($name);
	$name = str_replace("+", " ", $name);
	$name = str_replace("%21", "!", $name);
	$name = str_replace("%24", "$", $name);
	$name = str_replace("%27", "'", $name);
	$name = str_replace("%28", "(", $name);
	$name = str_replace("%29", ")", $name);
	$name = str_replace("%2B", "+", $name);

	return $name;
}


function decode_file_name($name)
{
	$name = urldecode($name);

	return $name;
}


function drive_is_file($drive_file)
{
	return ($drive_file["type"] == 1);
}


function drive_is_dir($drive_file)
{
	return ($drive_file["type"] == 0);
}


function drive_set($data)
{
	global $doc_root;
	global $server_id;

	//if (strlen($hash) != 64 || !string_uses($hash, "[0-9]abcdef")) {
	//	//die("invalid hash [$hash]");
	//	return false;
	//}

	if ($data === "") {
		return crypt_sha256("");
	}

	$hash = crypt_sha256($data);
	$path = "$doc_root/drive/" . substr($hash, 0, 3);
	$file = substr($hash, 3) . ".gz";
	$size = strlen($data);

	$drive_data = db_find_rec("drive_data", $hash);
	if ($drive_data === false) {
		$drive_data = db_new_rec("drive_data");
		$drive_data["hash"] = $hash;
		$drive_data["server_id"] = $server_id;
		$drive_data["size"] = $size;
	} else {
		if ($drive_data["size"] != $size) {
			//die("hash collision [$hash]");
			return false;
		}

		return $hash;
	}

	if (!fs_is_dir($path)) {
		if (!fs_make_dir($path)) {
			//die("unable to make dir [$path]");
			return false;
		}
	}

	if (!fs_is_file("$path/$file")) {
		if (!gz_slap("$path/$file", $data)) {
			//die("unable to save file [$hash]");
			return false;
		}

//		$fp = gzopen("$path/$file", "w9");
//		if ($fp === false) {
//			//die("unable to gzopen file [$hash]");
//			return false;
//		}
//		if (gzwrite($fp, $data) != $size) {
//			//die("unable to save entire file [$hash]");
//			return false;
//		}
//		gzclose($fp);
	}

	db_set_rec("drive_data", $drive_data);

	return $hash;
}


function drive_get($hash)
{
	global $doc_root;
	global $server_id;

	if (strlen($hash) != 64 || !string_uses($hash, "[0-9]abcdef")) {
		//die("invalid hash [$hash]");
		return false;
	}

	$drive_data = db_find_rec("drive_data", $hash);
	if ($drive_data === false) {
		return false;
	}

	if ($drive_data["server_id"] != $server_id) {
		//die("wrong server [$server_id]");
		return false;
	}

	$path = "$doc_root/drive/" . substr($hash, 0, 3);
	$file = substr($hash, 3) . ".gz";

	//die("file [$path/$file]");
	return gz_slurp("$path/$file");
}
