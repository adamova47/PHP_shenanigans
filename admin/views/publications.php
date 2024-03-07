<?php

// $pubId current pub entry 
$pubId = -1;
if (isset($_GET['id'])) {
	$pubId = $_GET['id'];
} elseif (isset($_POST['task']) && $_POST['task'] == "invokeEdit") {
	$pubId = intval($_POST['id']);
}

// $task to be done
$task = "";
if (isset($_POST['task'])) {
	$task = $_POST['task'];
}

include "publiForm.php";

// pagination
$page = 1;
if (isset($_GET['page']))
	$page = $_GET['page'];
elseif (isset($_POST['page']))
	$page = $_POST['page'];

$orderby = 'id';
if (isset($_GET['orderby']))
	$orderby = mysqli_escape_string($mysqli, $_GET['orderby']);
$order = 'ASC';
if (isset($_GET['order']))
	if (mysqli_escape_string($mysqli, $_GET['order']) == "desc")
		$order = 'DESC';

// query for all publications with filters    
$query = "";
if (isset($_GET['user'])) {
	$uid = userIdByName($_GET['user']);
	$query = 'SELECT * FROM ' . $publiTable . ' AS p, ' . $userxpub . ' AS rel WHERE id = rel.publication AND rel.user = ' . $uid;
} else {
	$query = 'SELECT * FROM ' . $publiTable;
}
$resCount = mysqli_query($mysqli, $query);
$entrycount = mysqli_num_rows($resCount);
$pagecount = (int) ($entrycount / $entrylimit) + 1;

$query .= ' ORDER BY ' . $orderby . ' ' . $order . ' LIMIT ' . $entrylimit . ' OFFSET ' . (($page - 1) * $entrylimit);
$res = mysqli_query($mysqli, $query);
?>
	</form>
	<div id="publinavi">
<?php

// list all publications
if ($res) {
	//are there more pages? >> page selection navigation
	if ($pagecount > 1) {
		$temp = $_GET;
		$temp['page'] = 1;
	?>
		<p>Page <strong><?=$page?>/<?=$pagecount?></strong></p>
		<a href="?<?=createGet($temp)?>#publinavi">first</a>		
		<?php
		if ($page > 1) {
			$temp['page'] = $page-1;
	?>
		<a href="?<?=createGet($temp)?>#publinavi">back &lt;&lt;</a> 
			<?php
			}
			if ($page < $pagecount) {
				$temp = $_GET;
				$temp['page'] = $page+1;
			?>
		<a href="?<?=createGet($temp)?>#publinavi">&gt;&gt; forward</a>
			<?php	
			}
			$temp['page'] = $pagecount;
			?>
		<a href="?<?=createGet($temp)?>#publinavi">last</a>
		<div class="clear"></div>
		<?php
	}

	//filter navigation - order by
	?>
		<p><strong>Order by:</strong></p>
			<?php
			foreach ($publiNaviFields as $val) {
				$temp = $_GET;
				$temp['orderby'] = $val;
				if (isset($_GET['orderby']) && $_GET['orderby'] == $val && isset($_GET['order']) && $_GET['order'] != 'desc') {
					$temp['order'] = 'desc';
				} else {
					$temp['order'] = 'asc';
				}
				?>
				<a href="?<?=createGet($temp)?>#publinavi"><?=$publiNaviNames[$val];?></a><?="\t"?>        
				<?php
			}
			?>
	</div>
	<div id="exportbib">
		<p>
		<form enctype="multipart/form-data" id="expbibform" name="expbibform" action="utils/exportBibLib.php" method="post">
			<select id="listTags[]" name="listTags[]" class="link" multiple="multiple" size="4">
				<option value="all">all</option>
				<option value="none">none</option>
				<?php
					$q3 = "SELECT * FROM ".$projTable;
					$r3 = mysqli_query($mysqli, $q3);
					if ($r3) {
						while ($row3 = mysqli_fetch_assoc($r3)) {
				?>
				<option value="<?=$row3['id']?>"><?=$row3['tag']?></option>
				<?php
						}
					}
				?>
			</select>				
			<select id="listUsers[]" name="listUsers[]" class="link" multiple="multiple" size="4">
				<option value="all">all</option>
				<?php
					$q3 = "SELECT * FROM ".$userTable;
					$r3 = mysqli_query($mysqli, $q3);
					if ($r3) {
						while ($row3 = mysqli_fetch_assoc($r3)) {
				?>
				<option value="<?=$row3['id']?>"<?=($user['id'] == $row3['id'])?' selected="selected"':''?>><?=usersFullName($row3['id'])?></option>
				<?php
						}
					}
				?>
			</select>
			<input type="button" value="export bibtex" onclick="exportBibLib()" class="button"/> 
		</form>
		</p>
	</div>
	<div class="clear"></div>
	<div id="publist">
	<?php
	////////////// LIST PUBLICATIONS /////////////////////
	$count = 1;
	while ($row = mysqli_fetch_assoc($res)) {
		$pubId = $row['id'];
		?>	    
		<div class="pbox <?= ($row['vis'] == 0) ? ' notvisible' : '' ?>">
			<strong>id <?= $pubId ?></strong>, 
			<?php
			$selusers = array();
			$r_selusers = mysqli_query($mysqli, "SELECT * FROM " . $userTable . " AS u," . $userxpub . " AS rel WHERE u.id = rel.user AND rel.publication = " . $pubId);
			if ($r_selusers) {
				while ($row2 = mysqli_fetch_assoc($r_selusers)) {
					array_push($selusers, '<strong>' . $row2['username'] . '</strong>');
				}
				mysqli_free_result($r_selusers);
			}
			?>
			users: <?= implode(", ", $selusers) ?><br /> 
			<hr />
			<em>bibType</em>: @<?= $row['ptype'] ?><br />
			<em>entryName</em>: <strong><?= $row['name'] ?></strong><br />
			<?php
			foreach ($bibfieldsSorted as $val) {
				if ($row[$val] != "") {
					if ($val == "year") {
						?>
						<em><?= $val ?></em>: <strong><?= $row[$val] ?></strong><br />   
						<?php
					} else {
						?>
						<em><?= $val ?></em>: <?= $row[$val] ?><br />        
						<?php
					}
				}
			}
			?>
			<hr />
			<?php
			if ($row['url'] != "") {
				?>		
				<strong>url: </strong><?= $row['url'] ?><br />
				<?php
			}
			?>
			<strong>tags:</strong>
			<?php
			$r_proj = mysqli_query($mysqli, "SELECT * FROM projects AS p, projectxpublication AS rel WHERE p.id = rel.project AND rel.publication = " . $pubId);
			if ($r_proj) {
				while ($rowproj = mysqli_fetch_assoc($r_proj)) {
					?>
					<a href="javascript:editProject(<?= $rowproj['id'] ?>)" title="<?= $rowproj['tag'] ?>"><?= $rowproj['tag'] ?></a>
					<?php
				}
				if (mysqli_num_rows($r_proj) == 0)
					echo 'none';
			}
			?>
			<br />
			<input type="button" onclick="editEntry(<?= $pubId ?>)" value="Edit"/>
			<?php
			if ($row['vis'] == 0) {
				?>
				<input type="button" class="green" onclick="makePublic(<?= $pubId ?>,1,'<?= $publiTable ?>')" value="Enable"/>
				<?php
			} else {
				?>	
				<input type="button" class="red" onclick="makePublic(<?= $pubId ?>,0,'<?= $publiTable ?>')" value="Disable"/>
				<?php
			}
			?>
			<input type="button" onclick="setTask('deleteEntry',<?= $pubId ?>)" value="Delete"/>
			<a href="utils/exportBib.php?id=<?=$pubId?>" target="blank">bibTex</a>
			<br />
		</div>
		<?php
		if ($count == 3) {
			?>
			<div class="clear"></div>
			<?php
			$count = 1;
		} else {
			$count++;
		}
	}
	mysqli_free_result($res);
} else {
	echo $errordatabase;
	//echo mysql_error();
}
?>
		<div class="clear"></div>
	</div>
