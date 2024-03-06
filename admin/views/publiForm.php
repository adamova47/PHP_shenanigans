<?php
/*
 * @inherited params: $id, $task
 */

$uId = (isset($_GET['user'])) ? userIdByName($_GET['user']) : -1;
$selusers = array(0 => $uId);

$latexAreaInput = (isset($_POST['latexarea']) && !($_POST['latexarea'] == "")) ? $_POST['latexarea'] : "";

if ($pubId > 0) {
	?>
	<h3>Edit entry <?= $pubId ?></h3>
	<?php
	$r_entry = mysql_query('SELECT * FROM ' . $publiTable . ' WHERE id = ' . $pubId);
	if ($r_entry) {
		$data = mysql_fetch_array($r_entry);
		mysql_free_result($r_entry);
	}
} else {
	?>
	<h3>New entry</h3>
	<?php
}

if ($task == "importLaTex" && $latexAreaInput != "") {
	$newdata = parseBibTex($latexAreaInput);
	foreach ($data as $index => $value) {
		if (array_key_exists($index, $newdata)) {
			$data[$index] = $newdata[$index];
		}
	}
}
?>	
<fieldset>		
<?php
if ($task == "editLatex") {
	include "bibForm.php";
} else {
	include "relationsForm.php";
	?>
			<div class="clear"></div>
			<?php
			include "bibForm.php";
			?>
			<label for="url">url: </label><input type="text" value="<?php echo (!empty($data)) ? $data['url'] : '' ?>" name="url" id="url"/><br />
			<label for="vis">visible: </label><input type="checkbox" id="vis" name="vis"<?php echo (isset($data['vis']) && $data['vis'] == 1) ? ' checked="checked"' : '' ?>/>
	<?php
}
?>
	<div class="clear"></div> 
		<?php
		if ($task != "editLatex") {
			if ($pubId > 0) {
			?>
		<input type="button" value="Edit" class="button" onclick="setTask('editEntry',<?= $pubId ?>)"/>
		<?php
			} else {
		?>
		<input type="button" value="Add" class="button" onclick="setTask('newEntry')"/>
		<?php
			}
		?>
		<input type="button" value="Edit LaTex" class="button" enabled="false" onclick="editLatex(<?= $pubId ?>)"/>
		<?php
		} 
		else {
		?>
		<input type="button" value="Import LaTex" class="button" enabled="false" onclick="importLaTex(<?= $pubId ?>)"/>
		<?php
		}
		?>	
</fieldset>
</div>
<div class="clear"></div>	
<hr/>