<?php
include "header.php";
?>

<div id="wrap">
	
<form enctype="multipart/form-data" action="" method="post" name="main">
	<input type="hidden" id="dbaction" name="dbaction" value=""/>
	<input type="hidden" id="task" name="task" value=""/>
	<input type="hidden" id="id" name="id" value=""/>
	<input type="hidden" id="part" name="part" value=""/>
	<input type="hidden" id="pagepart" name="pagepart" value=""/>
	<input type="hidden" id="vis" name="vis" value=""/>
	<input type="hidden" id="changeVis" name="changeVis" value=""/>
	<input type="hidden" id="tablename" name="tablename" value=""/>
	<input type="hidden" id="formid" name="formid" value="<?php echo $_SESSION['formid']; ?>"/>

	<? //print_r($_POST); ?>

	<div id="header">
		<h1>CNC admin menu: <?php echo $user['username']; ?></h1>
	</div>
	<?php
	$mysqli = db_connect();
	$username = $user['username'];

	if (isset($message) && $message != "") {
		?>	    
		<p class="message"><?= $message ?></p>
		<?php
	}
	$part = "";
	if (isset($_POST['part']) && $_POST['part'] !== "")
		$part = $_POST['part'];
	else if (isset($_GET['part']))
		$part = $_GET['part'];

	$action = "";
	if (isset($_GET['action']))
		$action = $_GET['action'];

	$mainTable = $user['tablename'];

	////////////// NAVIGATION /////////////////////

	?>
		<ul class="navi_hori">
	<?php
	if ($user['role'] > 0) {
		if ($action == 'cnchome')
			$mainTable = "home_cnc";
		elseif ($action == 'meicogsci')
			$mainTable = "home_meicogsci";
		?>
			<li><a href="?part=home"><strong>My Home</strong></a></li>
			<li><a<?= ($action == 'cnchome') ? ' class ="focus"' : '' ?> href="?action=cnchome"><strong>CNC Home</strong></a></li>
			<li><a<?= ($action == 'projects') ? ' class ="focus"' : '' ?> href="?action=projects"><strong>CNC projects</strong></a></li>
			<li><a<?= ($action == 'publications' && !isset($_GET['user'])) ? ' class ="focus"' : '' ?> href="?action=publications"><strong>Publications</strong></a></li>
			<li><a<?= ($action == 'bibchars') ? ' class ="focus"' : '' ?> href="?action=bibchars"><strong>BibTexChars</strong></a></li>
			<li><a<?= ($action == 'meicogsci') ? ' class ="focus"' : '' ?> href="?action=meicogsci"><strong>CogSci Home</strong></a></li>
			<li><a<?= ($action == 'aiseminar') ? ' class ="focus"' : '' ?> href="?action=aiseminar"><strong>AI Seminar</strong></a></li>
		<?php
		}
		?>					
			<li><a href="?action=logout">Logout</a></li>
			<div class="clear"></div>
		</ul>		
		<?php
			if (($action != "projects" && $action != "publications" && $action != "bibchars" && $action != "aiseminar") || 
			($action == "publications" && $user['externalPublications'] === "1"))
		{
		?>
		<div id="panel_left">
			<ul class="navi_verti">
				<?php		
					$navidata = mysqli_query($mysqli, "SELECT name,getname FROM " . $mainTable . " ORDER BY id ASC");

					if ($navidata) {

						while ($row = mysqli_fetch_assoc($navidata)) {

							$getname = $row['getname'];
							if ($row['getname'] == "")
								$getname = 'home';

							if ($getname == "publications") {
								$act = $getname;
								if ($action != "cnchome") 
									$act = $act. '&amp;user=' . $username;
								?>				    
								<li>
									<a href="?action=<?=$act?>"><?= $row['name'] ?></a>
								</li>
								<?php
							}
							elseif ($getname == "gallery") {
								$act = $getname;
								?>				    
								<li>
									<?= $row['name'] ?>
								</li>
								<?php
							}
							else {
								?>			
								<li>
									<a<?= ($part == $getname) ? ' class = "focus"' : '' ?> href="?<?= ($action != "") ? 'action=' . $action . '&amp;' : '' ?>part=<?= $getname ?>">
										<?= $row['name'] ?>
									</a>
								</li>
								<?php
							}
						}

						mysqli_free_result($navidata);
					}
				?>
			</ul>
		</div>
		<div id="panel_right">
		<?php
			} else {
		?>
			<div id="panel_center">
		<?php		
			}
	////////////// CONTENT ////////////////////////////////////////////
			if ($part == "" && $action != "projects" && $action != "publications" && $action != "bibchars" && $action != "aiseminar") {
				?>
				<p>Kliknutím na menu stránky sa zobrazí obsah podstránky</p>
				<?php
			} else {
				$page = 1;
				if (isset($_GET['page'])) {
					$page = $_GET['page'];
				} 
				elseif (isset($_POST['page'])) {
					$page = $_POST['page'];
				}
				if ($action == "publications") {
					include "views/publications.php";
				} 
				elseif ($action == "projects") {
					include "projects.php";
				} 
				elseif ($action == "bibchars") {
					include "bibChars.php";
				} 
				elseif ($action == "aiseminar") {
					include "aiseminar.php";
				} 
				else {
					$query = "SELECT * FROM " . $mainTable . " WHERE getname = '" . mysqli_real_escape_string($mysqli,$part) . "'";
					$res = mysqli_query($mysqli, $query);
					$id = 0;
					if ($res) {
						$row = mysqli_fetch_assoc($res);
						?>	
						<textarea id="novytext" name="novytext" rows="25" cols="80"><?php echo $row['text']?></textarea>
						<?php include "insertHtmlForm.php"; ?>
						<?php if ($mainTable != "home_cnc") { ?>
							<label for="hasleft"><strong>Has left panel:</strong></label><input type="checkbox" id="hasleft" name="hasleft"<?php echo (isset($row['has-left']) && $row['has-left'] == 1) ? ' checked="checked"' : '' ?>/><br />
						<?php } ?>	
						<input type="button" value="Change Entry" class="button" onclick="<?php echo "setTask('changeMain','" . $row['id'] . "','" . $part . "')" ?>"/>
						<?php
						mysqli_free_result($res);
					}
					else
						echo $errordatabase;
					
					if ($action != "projects" && $action != "publications" && $action != "bibchars") {
					?>					
				</div>
				<div class="clear"></div>
				<?php
				}
			}
		}
		if ($part != "publications") {
		?>
	</form>
		<?php
		}
		mysqli_close($mysqli);
		?>
	</div>


	<?php include "footer.php"; ?>
