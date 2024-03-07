<h2>Research Interests of the CNC Group</h2>

<?php
	$mysqli = db_connect();

	$q1 = 'SELECT * FROM '.$projTable.' WHERE vis = 1';
	$r1 = mysqli_query($mysqli, $q1);
	if ($r1) {
		while ($project = mysqli_fetch_array($r1)) {
?>
	<h3><?=$project['projectname']?></h3>
	<p><?=$project['description']?></p>
	<h4>Related publications:</h4>
	<div class="publ">
	<ul>
		<?php
			$q2 = 'SELECT * FROM '.$publiTable.' p, '.$projxpub.' rel
					WHERE rel.publication = p.id AND rel.project = '.$project['id'].' ORDER BY year DESC';
			$r2 = mysqli_query($mysqli, $q2);
			if ($r2) {
				$c = 0;
				$visible = true;
				$count = mysqli_num_rows($r2);

				while ($pub = mysqli_fetch_array($r2)) { 
					if ($pub['vis'] == 1) {
						echo "<li>";
						echo publToString($pub);
						echo "</li>"."\n";
						$c++;
					}

					if ($c > $visiblePubl && $c < $count && $visible == true) {
						$visible = false;
		?>
	</ul>
	<a href="#invisproj<?=$project['id']?>" data-toggle="collapse">... more</a>
	<div class="collapse" id="invisproj<?=$project['id']?>" name="invisproj<?=$project['id']?>">
		<ul>
			<?php
					}
				}
				if (!$visible) {
			?>
		</ul>
		<!--<a onclick="setInvisible('invisproj<?=$project['id']?>')">... less</a>-->		
		</div>
			<?php
					}
				}
			?>
	</ul>
	</div>
	<hr/>
		<?php
		}
	}
	mysqli_close($mysqli);