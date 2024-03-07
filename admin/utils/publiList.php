<?php
	include "../../common/conf.php";
    include "../../common/db_func.php";
    include "../../common/func.php";
	
	if (isset($_GET['invisId'])) {
		
		$visibleCount = $visiblePubl;
		if (isset($_GET['visibleCount']))
			$visibleCount = $_GET['visibleCount'];
		
		$tags = isset($_GET['tags']) ? json_decode($_GET['tags']) : array();
		$users = isset($_GET['users']) ? json_decode($_GET['users']) : array();

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
			$query = $query . ") ";
		}
		$query = $query . "ORDER BY p.year DESC";

		$result = mysqli_query($mysqli, $query);
		if ($result) {
			echo "\n" . '<div class="publ">' . "\n";
			echo "\t" . "\t" . "<ul>" . "\n";
			$c = 0;
			$visible = true;
			$count = mysqli_num_rows($result);
			while ($pub = mysqli_fetch_array($result)) {
				if ($pub['vis'] == 1) {
					echo "\t" . "\t" . "\t" . "<li>" . publToString($pub) . "</li>" . "\n";
					$c++;
				}
				if ($c >= $visibleCount && $c < $count && $visible) {
					$visible = false;
					echo "\t" . "\t" . "</ul>" . "\n";
					echo "\t" . "\t" . "<a onclick=\"setVisible('invisbox" . $_GET['invisId'] . "')\">... more</a>" . "\n";
					echo "\t" . "<div class=\"invisible\" id=\"invisbox" . $_GET['invisId'] . "\" name=\"invisbox" . $_GET['invisId'] . "\">" . "\n";
					echo "\t" . "\t" . "<ul>" . "\n";
				}
				if ($c == $count && !$visible) {
					echo "\t" . "\t" . "</ul>" . "\n";
					echo "\t" . "\t" . "<a onclick=\"setInvisible('invisbox" . $_GET['invisId'] . "')\">... less</a>" . "\n";
					echo "\t" . "</div>" . "\n";
				}
			}
			if ($c <= $visibleCount) 
				echo "</ul>" . "\n";
			echo "</div>" . "\n";
			mysqli_free_result($result);
		}
	}
?>
