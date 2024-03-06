<?php
////				CREATE HTML code						////

function htmlButtons($where) {
     echo '<input type="button" class="addHTML" onclick="addHTML(\'<h3>TEXT</h3>\',\''.$where.'\')" value="heading H3"/>'."\n";
     echo '<input type="button" class="addHTML" onclick="addHTML(\'<h4>TEXT</h4>\',\''.$where.'\')" value="heading H4"/>'."\n";
     echo '<input type="button" class="addHTML" onclick="addHTML(\'<p>TEXT</p>\',\''.$where.'\')" value="paragraph"/>'."\n";
     echo '<input type="button" class="addHTML" onclick="addHTML(\'<ul>\\n<li>TEXT</li>\\n<li></li>\\n</ul>\',\''.$where.'\')" value="bullets"/>'."\n";
     echo '<input type="button" class="addHTML" onclick="addHTML(\'<ol>\\n<li>TEXT</li>\\n<li></li>\\n</ol>\',\''.$where.'\')" value="numbering"/>'."\n";
}

function htmlButtonsH2($where) {
     echo '<input type="button" class="addHTML" onclick="addHTML(\'<h2>TEXT</h2>\',\''.$where.'\')" value="heading H2"/>'."\n";
     htmlButtons($where);
}

function usersAsOptions($selusers) {
	//selusers > array
	$out = "";
	$r_users = mysql_query("SELECT * FROM users");
	if ($r_users) {
		while ($data = mysql_fetch_assoc($r_users)) { 
			$out = $out . '<option value="'.$data['id'].'"';
			if (!empty($selusers) && in_array($data['id'],$selusers))
				$out = $out . ' selected = "selected"';
			$out = $out . '>'.$data['firstname'].' '.$data['surname'].'</option>'."\n";
		}
	}
	return $out;
}

function projectsAsOptions($selprojects) {
	//selprojects > array
	$out = "";
	$r_projects = mysql_query("SELECT * FROM projects");
	if ($r_projects) {
		while ($data = mysql_fetch_assoc($r_projects)) { 
			$out = $out . '<option value="'.$data['id'].'"';
			if ($selprojects != null && in_array($data['id'],$selprojects))
				$out = $out . ' selected = "selected"';
			$out = $out . '>'.$data['tag'].'</option>'."\n";
		}
		mysql_free_result($r_projects);
	}
	return $out;
}

////			USER functions							////

function userIdByName($name) {
	//selusers > array
	$userid = "";
	$q = "SELECT * FROM users WHERE username = '".$name."'";
	//echo $q;
	$r_user = mysql_query($q);
	if ($r_user) {
		while ($row = mysql_fetch_assoc($r_user)) { 
			$userid = $row['id'];
		}
		mysql_free_result($r_user);
	} else {
		$userid = '-1';
	}
	$userid = intval($userid);
	return $userid;
}

////				BIB								////

function parseBibTex($input) {
	$data = array();
	$temp = explode("\n",$input);
	$frst = 0;
	foreach ($temp as $line) {
		$line = trim($line);
		if ($line == "}" || $line == "") {
			;
		} elseif ($frst == 0) {
			$data['ptype'] = trim(substr($line, 1, strpos($line, "{")));
			$data['name'] = trim(substr($line, strpos($line, "{")+1, strlen($line)-1));
			$data['name'] = substr($data['name'],0,strlen($data['name'])-1);
			$frst++;
		} else {
			$index = trim(substr($line, 0, strpos($line, "=")));
			$value = trim(substr($line, strpos($line, "=")+1, strlen($line) - 1));
			$value = substr($value, 1, strlen($value)-3);
			if ($index == "title")
				$value = substr($value, 1, strlen($value)-2);		
			if ($index != "") 
				$data[$index] = decodeBib($value);
		}
	}
	return $data;
}

?>

