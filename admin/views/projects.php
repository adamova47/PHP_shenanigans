<?php
	$data = array();
	$pid = -1;
	
	if (isset($_POST['task']) && $_POST['task'] == "invokeEdit") { 
		$pid = intval($_POST['id']);
	}
	elseif (isset($_GET['id'])) {
		$pid = intval($_GET['id']);
	}
	
	if ($pid > 0) {
?>
		<h3>Edit project <?=$pid?></h3>
<?php          
		$r_proj = mysqli_query($mysqli, 'SELECT * FROM '.$projTable.' WHERE id = '.$pid);
		if ($r_proj) {
			$data = mysqli_fetch_array($r_proj);
			mysqli_free_result($r_proj);
		} 
	} else {
?>
		<h3>New project</h3>
<?php
	}
	
	$selusers = array();
	if (!(empty($data))) {
		$r_projUsers = mysqli_query($mysqli, "SELECT * FROM ".$userxproj." WHERE project = ".$data['id']);
		if ($r_projUsers) {
			$i = 0;
			while ($row = mysqli_fetch_assoc($r_projUsers)) { 
				$selusers[$i] = $row['user'];
				$i++;
			}
			mysqli_free_result($r_projUsers);
		}
	}
?>	
		<fieldset>
			
			<div class="box">
				<label for="addusers">add users: </label><select name="addusers[]" id="addusers[]" multiple="multiple" size="6"><?php echo usersAsOptions(null);?></select><br/>
				<label for="vis">visible:</label><input type="checkbox" id="vis" name="vis"<?php echo (isset($data['vis']) && $data['vis'] == 1) ? ' checked="checked"'  : ''?>/>
			</div>	
<?php if ($pid > 0) {
?>			
			<div class="box">
				<label for="deleteusers">delete users: </label><select name="deleteusers[]" id="deleteusers[]" multiple="multiple" size="6"><?php echo usersAsOptions(null);?></select><br/>
			</div>	
<?php }  ?>
			<div class="clear"></div>
			
			<label for="tag">tag name:</label><input type="text" name="tag" id="tag" class="long" value="<?php if (!(empty($data['tag']))) echo $data['tag']?>"/><br />
			<label for="projectname">title:</label><input type="text" name="projectname" id="projectname" class="long" value="<?php if (!(empty($data['projectname']))) echo $data['projectname'];?>"/><br />
			<label for="description">description:</label>
			<textarea name="description" id="description" rows="10" cols="45" class="smallarea">
				<?php if (isset($data['description'])) echo $data['description'];?>
			</textarea>
			<div class="right">	
				<?php include "insertHtmlForm.php";?>
			</div>		

<?php
	if ($pid > 0) {
?>
			<input type="button" value="Edit" class="button" onclick="setTask('editProject',<?=$pid?>)"/>
<?php
	} else {
?>
			<input type="button" value="Add" class="button" onclick="setTask('newProject')"/>
<?php
	}           
?>	
			</div>
			<div class="clear"></div>
		</fieldset>
		
<!--	// TABLE OF ENTRIES -->
<?php
	$get = getString();
?>
	<table cellpadding="0" cellspacing="0">
		<tr>
<?php
	$fields = array("id", "vis", "tag", "projectname", "description", "users");
	foreach ($fields as $val) {
		$width = 250;
		if ($val == "id" || $val == "vis")
			$width = 40;
		elseif ($val == "description")
		     $width = 600;
		elseif ($val == "tag")
		     $width = 120;
 ?>
			<th width="<?=$width?>"><a href="?<?=$get.'orderby='.$val?>"><?=$val?></a></th>
<?php
	}
?>
			<th width="80">Action</th>
		</tr>
<?php		
	$orderby = 'id';
	if (isset($_GET['orderby']))
		$orderby = mysqli_escape_string($mysqli, $_GET['orderby']);

	$query = 'SELECT * FROM '.$projTable;
	$query = $query.' ORDER BY '.$orderby;
	$query = ($orderby == 'id' || $orderby == 'vis') ? $query.' DESC ' : $query.' ASC ';
	$res = mysqli_query($mysqli, $query);
	if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
?>
		<tr>
<?php
			foreach ($fields as $val) {
?>
				<td	<?php if ($row['vis'] == 0) echo 'class="gray"'?>>
<?php
				if ($val == "vis")
					echo ($row[$val] == 0) ? "no" : "yes"; 
				elseif ($val == "users") {
					$r_users = mysqli_query($mysqli, "SELECT * FROM ".$userxproj." WHERE project = ".$row['id']);
					if ($r_users) {
						while ($r2 = mysqli_fetch_assoc($r_users)) {
							echo usersFullName($r2['user']).'<br />';
						}
					}
				}
				else
					echo $row[$val];
?>
				</td>
<?php		
			}
?>
				<td>
					<input type="button" onclick="editEntry(<?=$row['id']?>)" value="Edit"/>
<?php
		    if ($row['vis'] == 0) {
?>
				<input type="button" class="green" onclick="makePublic(<?=$row['id']?>,1,'<?=$projTable?>')" value="Enable"/>
<?php
			} else {
?>
				<input type="button" class="red" onclick="makePublic(<?=$row['id']?>,0,'<?=$projTable?>')" value="Disable"/>
<?php
			}
?>	
				<input type="button" onclick="setTask('deleteProject',<?=$row['id']?>)" value="Delete"/><br/>
			</td>
			</tr>
<?php
		}
	}
?>	
	</table>
<br/>