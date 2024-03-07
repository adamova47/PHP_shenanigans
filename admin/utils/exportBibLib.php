<?php
	include "../../common/conf.php";
    include "../../common/db_func.php";
    include "../../common/func.php";
    include "adminfunc.php";
	$output = "";
	$mysqli = db_connect();
		
	$tags = array();
	if (isset($_POST['listTags'])) {
		foreach ($_POST['listTags'] as $i) {
			array_push($tags, $i);
		}
	}
	$users = array();
	if (isset($_POST['listUsers'])) {
		foreach ($_POST['listUsers'] as $i) {
			array_push($users, $i);
		}
	}
	
	if (empty($tags) && empty($users)) {
		$query = "SELECT * FROM ".$publiTable." p ";
	} 
	elseif ((empty($tags) || in_array("all", $tags)) && !empty($users)) {
		$query = "SELECT * FROM ".$publiTable." p, ".$userxpub." uxp WHERE p.id = uxp.publication AND uxp.user IN (".implode(", ", $users).") ";
	}
	elseif ((empty($users) || in_array("all", $users)) && !empty($tags)) {
		$query = "SELECT * FROM ".$publiTable." p, ".$projxpub." pxp WHERE p.id = pxp.publication AND pxp.project IN (".implode(", ", $tags).") ";
	} 
	else {
		$query = 
			"SELECT * FROM ".$publiTable." p WHERE p.id IN (".
			"SELECT pxp.publication FROM ".$projxpub." pxp LEFT JOIN ".$userxpub." uxp ON pxp.publication = uxp.publication ".
			"WHERE pxp.project IN (".implode(", ", $tags).") ";
		$query = (in_array("all", $tags) || empty($tags)) ? $query." WHERE " : $query." AND ";
		$query = $query."uxp.user IN (".implode(", ", $users).")";
		$query .= ") ";
	}
	$query = $query . "ORDER BY p.year DESC";

//		echo $query;
	
	$result = mysqli_query($mysqli, $query);
	if ($result) {
		while ($data = mysqli_fetch_array($result)) { 
			$attributes = $bibTypesArr[$data['ptype']];
			
			if ($data['vis'] == '1') {
				$output = $output."@".$data['ptype']." {"."\n";

				foreach ($attributes as $att) {
					if ($data[$att] != "") {
						$output = $output.$att."={";
						$output = ($att=="author"||$att=="title")?$output."{":$output;
						$output = $output.encodeBib($data[$att]);
						$output = ($att=="author"||$att=="title")?$output."}":$output;
						$output = $output."}"."\n";
					}
				}
				$output = $output."}"."\n"."\n";
			}
			
		}
		mysqli_free_result($result);
	}

	Header('Content-Type: application/txt');
	Header('Content-Length: '.strlen($output));
	Header('Content-disposition: inline; filename=myLibrary.bib');
	echo $output;  
?>