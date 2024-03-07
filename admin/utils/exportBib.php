<?php
	include "../../common/conf.php";
    include "../../common/db_func.php";
    include "../../common/func.php";
    include "adminfunc.php";
	
	if (db_connect()) {
		$data = array();
		$pubId = (isset($_GET['id'])) ? $_GET['id'] : -1;

		$query = "SELECT * FROM publications WHERE id = ".$pubId;
		$res = mysqli_query($mysqli, $query);
		if ($res) {
			$data = mysqli_fetch_array($res);
			mysqli_free_result($res);
		}

		$attributes = $bibTypesArr[$data['ptype']];

		echo "@".$data['ptype']." {"."<br/>";
		foreach ($attributes as $att) {
			if ($data[$att] != "") {
				echo $att."={";
				echo ($att=="author"||$att=="title")?"{":"";
				echo encodeBib($data[$att]);
				echo ($att=="author"||$att=="title")?"}":"";
				echo "}<br/>";
			}
		}
		echo "}";
	}

?>
