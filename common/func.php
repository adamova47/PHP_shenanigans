<?php
function getString() {
	if (isset($_GET)) {
		$get = "";
		foreach ($_GET as $key => $value)
			$get = $get . $key . '=' . $value . '&amp;';
		return $get;
	} else {
		return null;
	}
}

function usersFullName($userid) {
	//selusers > array
	$userid = intval($userid);
	$out = "";
	$mysqli = db_connect();
	$r_user = mysqli_query($mysqli, "SELECT * FROM users WHERE id = " . $userid);
	if ($r_user) {
		while ($row = mysqli_fetch_assoc($r_user)) {
			$out = $row['firstname'] . ' ' . $row['surname'];
		}
		mysqli_free_result($r_user);
	}
	mysqli_close($mysqli);
	return $out;
}

function publToString($data) {

	$out = "";

	$author = $data['author'];
	if (strpos($author, 'and'))
		str_replace('and', ',', $author);

	$title = $data['title'];
	if ($data['url'] != "")
		$title = $data['title'];

	if ($data['ptype'] == "article") {
		//Required fields: author, title, journal, year
		//Optional fields: volume, number, pages, month, note, key
		$out = $out . $author . ": ";
		$out = $out . $title . ". ";
		$out = $out . '<em>' . $data['journal'] . '</em>';
		if ($data['volume'] != "")
			$out = $out . " " . $data['volume'];
		if ($data['number'] != "")
			$out = $out . "(" . $data['number'] . "). ";
		if ($data['pages'] != "")
			$out = $out . $data['pages'] . ". ";
		if ($data['month'] != "")
			$out = $out . ". " . $data['month'] . " ";
		$out = $out . " " . $data['year'] . ".";
		if ($data['note'] != "")
			$out = $out . " " . $data['note'] . ".";
	}
	elseif ($data['ptype'] == "conference" || $data['ptype'] == "incollection" || $data['ptype'] == "inproceedings") {
		//Required fields: author, title, booktitle, year
		//Optional fields: editor, pages, organization, publisher, address, month, note, key
		$out = $out . $author . ": ";
		$out = $out . $title . ". In ";
		if ($data['editor'] != "")
			$out = $out . $data['editor'] . " (eds.), ";
		$out = $out . '<em>' . $data['booktitle'] . '</em>';
		if ($data['publisher'] != "")
			$out = $out . ", ";
		else
			$out = $out . ". ";
		if ($data['publisher'] != "") {
			$out = $out . $data['publisher'];
			if ($data['address'] != "")
				$out = $out . ", " . $data['address'] . ". ";
			else
				$out = $out . ". ";
		}
		if ($data['pages'] != "")
			$out = $out . $data['pages'] . ". ";
		if ($data['month'] != "")
			$out = $out . $data['month'] . " ";
		$out = $out . $data['year'] . ".";
		if ($data['note'] != "")
			$out = $out . " " . $data['note'] . ".";
	}
	elseif ($data['ptype'] == "book" || $data['ptype'] == "inbook") {
		//Required fields: author/editor, title, publisher, year
		//Optional fields: volume, series, address, edition, month, note, key
		$out = $out . $author . ": ";
		$out = $out . $title;
		if ($data['volume'] != "")
			$out = $out . "(vol. " . $data['volume'] . ")";
		$out = $out . ". ";
		$out = $out . $data['publisher'];
		if ($data['address'] != "")
			$out = $out . ", " . $data['address'] . ". ";
		else
			$out = $out . ". ";
		if ($data['month'] != "")
			$out = $out . $data['month'] . " ";
		$out = $out . $data['year'] . ".";
		if ($data['ptype'] == "inbook" && $data['pages'] != "") {
			$out = $out . " pages: " . $data['pages'];
		}
		if ($data['note'] != "")
			$out = $out . " " . $data['note'] . ".";
	}
	elseif ($data['ptype'] == "mastersthesis" || $data['ptype'] == "phdthesis") {
		//Required fields: author, title, school, year
		//Optional fields: address, month, note, key
		$out = $out . $author . ": ";
		$out = $out . $title . ". ";
		$out = $out . $data['school'];
		if ($data['address'] != "")
			$out = $out . ", " . $data['address'] . ". ";
		else
			$out = $out . ". ";
		if ($data['month'] != "")
			$out = $out . $data['month'] . " ";
		$out = $out . $data['year'] . ".";
		if ($data['note'] != "")
			$out = $out . " " . $data['note'] . ".";
	}
	elseif ($data['ptype'] == "techreport") {
		//Required fields: author, title, institution, year
		//Optional fields: type, number, address, month, note, key
		$out = $out . $author . ": ";
		$out = $out . $title . ". ";
		$out = $out . "Technical report";
		if ($data['number'] != "")
			$out = $out . " " . $data['number'];
		$out = $out . ". ";
		$out = $out . $data['institution'];
		if ($data['address'] != "")
			$out = $out . ", " . $data['address'] . ". ";
		else
			$out = $out . ". ";
		if ($data['month'] != "")
			$out = $out . $data['month'] . " ";
		$out = $out . $data['year'] . ".";
		if ($data['note'] != "")
			$out = $out . " " . $data['note'] . ".";
	}
	else {
		//$req = array('author', 'title', 'year');
		//$opt = array('pages', 'month', 'note', 'pub_key');
		$out = $out . $author . ": ";
		$out = $out . $title . ". ";
		$out = $out . $data['year'] . ".";
		if ($data['note'] != "")
			$out = $out . " " . $data['note'] . ".";
	}

	if (isset($data['url']) && $data['url'] != "")
		$out = $out . '<a href="' . $data['url'] . '"><img src="../common/images/pdf_icon.png" width="25" height="15" alt="pdf"/></a>';

	$out = $out . '<a href="http://cogsci.fmph.uniba.sk/cnc/exportBib.php?id='.$data['id'].'" target="_blank"><img src="../common/images/icon-bib.png" width="22" height="15" alt="bib"/></a>';

	return $out;
}

function encodeBib($string) {
	$mysqli = db_connect();
	$string = mysqli_escape_string($mysqli, $string);
	$specChars = downloadSpecChars();
	$out = "";
	foreach (getCharArray($string) as $char) {
		//echo $char."|";
		if (array_key_exists($char, $specChars)) {
			$out = $out . $specChars[$char];
		} else {
			$out = $out . $char;
		}
	}
	return $out;
}

function decodeBib($string) {
	$specChars = downloadSpecChars();
	foreach ($specChars as $char => $code) {
		if (strpos($string, $code) >= 0) {
			$string = str_replace($code, $char, $string);
		}
	}
	return $string;
}

function downloadSpecChars() {
	$mysqli = db_connect();
	$query = "SELECT * FROM bibtex_chars";
	$result = mysqli_query($mysqli, $query);
	$data = array();
	if ($result) {
		while ($row = mysqli_fetch_assoc($result)) {
			$data[$row['char']] = $row['bibcode'];
			$row['char'];
			//echo $row['char'].":".$data[$row['char']]."<br/>";
		}
	}
	return $data;
}

function getCharArray($jstring) {
	$len = mb_strlen($jstring, 'UTF-8');
	if (mb_strlen($jstring, 'UTF-8') == 0)
		return array();

	$ret = array();
	for ($i = 0; $i < $len; $i++) {
		$char = mb_substr($jstring, $i, 1, 'UTF-8');
		array_push($ret, $char);
	}

	return $ret;
}

function createBib($data) {

	$bibTypesArray = array(
		"article" => array("author", "title", "year", "pages", "month", "note", "journal", "volume", "number"),
		"book" => array("author", "title", "year", "editor", "publisher", "series", "address", "edition", "pages", "note"),
		"inbook" => array("author", "title", "year", "editor", "publisher", "series", "address", "edition", "pages", "note"),
		"conference" => array("author", "title", "booktitle", "year", "editor", "publisher", "series", "address", "organization", "pages", "note"),
		"incollection" => array("author", "title", "year", "editor", "series", "address", "edition", "booktitle", "organization", "pages", "note"),
		"inproceedings" => array("author", "title", "booktitle", "year", "editor", "publisher", "series", "address", "organization", "pages", "note"),
		"mastersthesis" => array("author", "title", "school", "year", "pages", "note"),
		"phdthesis" => array("author", "title", "school", "year", "pages", "note"),
		"techreport" => array("author", "title", "institution", "year", "pages", "note"),
		"misc" => array("author", "title", "year", "month", "note"),
		"unpublished" => array("author", "title", "year", "month", "note"),
	);

	$out = "";
	$ptype = "" . $data['ptype'];
	$attributes = $bibTypesArray[$ptype];

	$out .= "@" . $ptype . " {" . $data['name'] . "<br/>";
	foreach ($attributes as $att) {
		if ($data[$att] != "") {
			$out .= $att . "={";
			$out .= ($att == "author" || $att == "title") ? "{" : "";
			$out .= encodeBib($data[$att]);
			$out .= ($att == "author" || $att == "title") ? "}" : "";
			$out .= "},<br/>";
		}
	}
	$out .= "}";

	return $out;
}

function createNavi($mainTable) {
	$mysqli = db_connect();
	$out = "";
	$q_navidata = "SELECT name,getname FROM " . $mainTable . " WHERE primarytext = '1' ORDER BY id ASC";
	$r_navidata = mysqli_query($mysqli, $q_navidata);
	if ($r_navidata) {
		$out.= '<ul>' . "\n";
		while ($row = mysqli_fetch_assoc($r_navidata)) {
			if ($row['getname'] == "home")
				$out.= '<li><a href="?">' . $row['name'] . '</a></li>' . "\n";
			else
				$out.= '<li><a href="?action=' . $row['getname'] . '">' . $row['name'] . '</a></li>' . "\n";
		}
		$out.= '</ul>' . "\n";
		mysqli_free_result($r_navidata);
	}	
	return $out;
}

function createNaviJustLi($mainTable) {
	$mysqli = db_connect();
	$out = "";
	$q_navidata = "SELECT name,getname FROM " . $mainTable . " WHERE primarytext = '1' ORDER BY id ASC";
	$r_navidata = mysqli_query($mysqli, $q_navidata);
	if ($r_navidata) {
		while ($row = mysqli_fetch_assoc($r_navidata)) {
			if ($row['getname'] == "home")
				//$out.= '<li><a href="?">' . $row['name'] . '</a></li>' . "\n";
				;
			else
				$out.= '<li><a href="?action=' . $row['getname'] . '">' . $row['name'] . '</a></li>' . "\n";
		}
		mysqli_free_result($r_navidata);
	}
	return $out;
}

function createNaviPriority($mainTable) {
	$mysqli = db_connect();
	$out = "";
	$q_navidata = "SELECT name,getname FROM " . $mainTable . " WHERE primarytext = '1' ORDER BY priority ASC";
	$r_navidata = mysqli_query($mysqli, $q_navidata);
	if ($r_navidata) {
			$out.= '<ul>' . "\n";
			while ($row = mysqli_fetch_assoc($r_navidata)) {
					if ($row['getname'] == "home")
							$out.= '<li><a href="?">' . $row['name'] . '</a></li>' . "\n";
					else
							$out.= '<li><a href="?action=' . $row['getname'] . '">' . $row['name'] . '</a></li>' . "\n";
			}
			$out.= '</ul>' . "\n";
			mysqli_free_result($r_navidata);
	}
	return $out;
}

function createContent($mainTable,$getname,$leftcol) {
	$mysqli = db_connect();
	$out = "";
	$q = "SELECT * FROM ".$mainTable." WHERE getname = '".mysqli_escape_string($mysqli, $getname)."'";
	$r_mainbody = mysqli_query($mysqli, $q);
	if ($r_mainbody) {
		if ($leftcol && mysqli_fetch_assoc($r_mainbody, 0, "has-left")) {
			$r_panel_left = mysqli_query($mysqli, "SELECT text FROM " . $mainTable . " WHERE name = 'panel_left'");
			if ($r_panel_left) {
				$out = $out.'<div id="content-panel-left">'.mysqli_fetch_assoc($r_panel_left, 0, "text").'</div>'."\n";
				mysqli_free_result($r_panel_left);
				$out = $out.'<div id="content-panel-right">'.mysqli_fetch_assoc($r_mainbody, 0, "text").'</div>'."\n";
				$out = $out.'<div class="clear"></div>'."\n";
			}
		} else
			$out = $out . mysqli_fetch_assoc($r_mainbody, 0, "text") . "\n";
		mysqli_free_result($r_mainbody); 
	}
	return $out;
}

function createContentNew($mainTable,$getname,$leftcol) {
	$mysqli = db_connect();
	$out = "";
	$q = "SELECT * FROM ".$mainTable." WHERE getname = '".mysqli_escape_string($mysqli, $getname)."'";
	$r_mainbody = mysqli_query($mysqli, $q);
	if ($r_mainbody) {
		if ($leftcol && mysqli_fetch_assoc($r_mainbody, 0, "has-left")) {
			$r_panel_left = mysqli_query($mysqli, "SELECT text FROM " . $mainTable . " WHERE name = 'panel_left'");
			if ($r_panel_left) {
				$out = $out.'<div class="col-md-4 panleft">'.mysqli_fetch_assoc($r_panel_left, 0, "text").'</div>'."\n";
				mysqli_free_result($r_panel_left);
				$out = $out.'<div class="col-md-8">'.mysqli_fetch_assoc($r_mainbody, 0, "text").'</div>'."\n";
			}
		} else
			$out = $out . '<div class="col-md-12">' . mysqli_fetch_assoc($r_mainbody, 0, "text") . '</div>'. "\n";
		mysqli_free_result($r_mainbody);
	}
	return $out;
}

function createFooter($mainTable) {
	$mysqli = db_connect();
	$out = "";
	$q = "SELECT * FROM " . $mainTable . " WHERE name = 'footer'";
	$r_footer = mysqli_query($mysqli, $q);
	if ($r_footer) {
		$out.= mysqli_fetch_assoc($r_footer, 0, "text")."\n";
		mysqli_free_result($r_footer);
	}
	return $out;
}

function createGet($params) {
	$out = "";
	foreach ($params as $key => $val) {
		$out .= $key . "=" . $val . "&amp;";
	}
	$out = substr($out, 0, strlen($out) - 5);
	return $out;
}

function listDirFiles($dirPath) {
	$fileList = array();
	$counter = 1;
	if (file_exists($dirPath)) {
		if ($handle = opendir($dirPath)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					if (is_dir("$dirPath/$file")) {
					} else {
						$fileList[$counter] = $file;
						$counter++;
					}
				}
			}
			closedir($handle);
		} else
			echo "error opening file";
	}
	return $fileList;
}

?>
