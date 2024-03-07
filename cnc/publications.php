<h2>Publications of the CNC Group</h2>

<div class="publ">
<ul>

<?php

     //id`, `ptype`, `address`, `author`, `booktitle`, `edition`, `editor`, `institution`, `journal`, `pub_key`, `month`, `note`, `number`, `organization`, `pages`, `publisher`, `school`, `series`, `title`, `volume`, `year`

	//$query = 'SELECT * FROM '.$publiTable.' AS p, '.$projxpub.' AS rel WHERE id = rel.publication AND rel.project = '.$uid.' ORDER BY p.year DESC';
	//$query = 'SELECT * FROM '.$publiTable.' AS p, '.$projxpub.' AS rel WHERE id = rel.publication ORDER BY p.year DESC';
	
	//TODO: sort by projects, users, year
	$mysqli = db_connect();

	$query = 'SELECT * FROM '.$publiTable.' ORDER BY year DESC';
	$res = mysqli_query($mysqli, $query);

	if ($res) {
		$row = array();
		$c = 0;
		while ($row = mysqli_fetch_array($res)) { 
			if ($row['vis'] == 1) {
				echo "<li>";
				echo publToString($row);
				echo "</li>"."\n";
				$c++;
			}
		}
		if ($c > 12)
			echo '<p class="top"><a href="#header">to the top</a></p>';

		mysqli_free_result($res);
	} else
		//echo "Q. failed: ".$query."<br/>";

	mysqli_close($mysqli);
?>

</ul>
</div>
