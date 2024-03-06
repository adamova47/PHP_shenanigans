<h2>Publications of the CNC Group</h2>

<div class="publ">
<ul>

<?php

     //id`, `ptype`, `address`, `author`, `booktitle`, `edition`, `editor`, `institution`, `journal`, `pub_key`, `month`, `note`, `number`, `organization`, `pages`, `publisher`, `school`, `series`, `title`, `volume`, `year`

	//$query = 'SELECT * FROM '.$publiTable.' AS p, '.$projxpub.' AS rel WHERE id = rel.publication AND rel.project = '.$uid.' ORDER BY p.year DESC';
	//$query = 'SELECT * FROM '.$publiTable.' AS p, '.$projxpub.' AS rel WHERE id = rel.publication ORDER BY p.year DESC';
	
	//TODO: sort by projects, users, year
	
	$query = 'SELECT * FROM '.$publiTable.' ORDER BY year DESC';
	$res = mysql_query($query);
	if ($res) {
		$row = array();
		$c = 0;
		while ($row = mysql_fetch_array($res)) { 
			if ($row['vis'] == 1) {
				echo "<li>";
				echo publToString($row);
				echo "</li>"."\n";
				$c++;
			}
		}
		if ($c > 12)
			echo '<p class="top"><a href="#header">to the top</a></p>';
	} else
		//echo "Q. failed: ".$query."<br/>";
?>

</ul>
</div>
