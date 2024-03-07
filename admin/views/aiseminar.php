<?php
$data = array();
$id = -1;
if (isset($_POST['task']) && $_POST['task'] == "invokeEdit") {
	$id = intval($_POST['id']);
} elseif (isset($_GET['id'])) {
	$id = intval($_GET['id']);
}

if ($id > 0) {
	?>
	<h3>Edit event <?= $id ?></h3>
	<?php
	$result = mysqli_query($mysqli, 'SELECT * FROM aiseminar WHERE id = ' . $id);
	if ($result) {
		$data = mysqli_fetch_array($result);
		mysqli_free_result($result);
	}
} else {
	?>
	<h3>New event</h3>
	<?php
}
?>
<fieldset>
	<label for="datetime"><strong>date:</strong></label><input type="text" class="" id="datetime" name="datetime" value="<?php if (!(empty($data['datetime']))) echo $data['datetime'] ?>" style="display: block;"><br />
	<label for="note">note:</label><input type="text" name="note" class="long" value="<?php if (!(empty($data['note']))) echo $data['note'] ?>"/><br />
	<label for="lecturer"><strong>lecturer:</strong></label><input type="text" name="lecturer" class="long" value="<?php if (!(empty($data['lecturer']))) echo $data['lecturer'] ?>"/><br />
	<label for="lecturerfrom">lecturer from:</label><input type="text" name="lecturerfrom" class="long" value="<?php if (!(empty($data['lecturerfrom']))) echo $data['lecturerfrom'] ?>"/><br />
	<label for="url">url:</label><input type="text" name="url" class="long" value="<?php if (!(empty($data['url']))) echo $data['url'] ?>"/><br />
	<label for="title"><strong>title:</strong></label><input type="text" name="title" class="long" value="<?php if (!(empty($data['title']))) echo $data['title'] ?>"/><br />
	<label for="abstract"><strong>abstract:</strong></label>
	<textarea name="abstract" id="description" rows="10" cols="65" class="smallarea"><?php if (isset($data['abstract'])) echo $data['abstract']; ?></textarea>
	<div class="right">
		<?php htmlButtons('abstract'); ?>
	</div>
	<div class="clear"></div>
	<?php
	if ($id > 0) {
		?>
		<input type="button" value="Edit" class="button" onclick="setTask('editSeminar',<?= $id ?>)"/>
		<?php
	} else {
		?>
		<input type="button" value="Add" class="button" onclick="setTask('newSeminar')"/>
		<?php
	}
	?>	
</fieldset>
</div>
<div class="clear"></div>	

<!--	// TABLE OF ENTRIES -->
<?php
$get = getString();
$limit = 20;
?>
<table cellpadding="0" cellspacing="0">
	<tr>
		<?php
		foreach ($aiSeminarFields as $val) {
			$width = 250;
			if ($val == "id" || $val == "vis")
				$width = 50;
			elseif ($val == "abstract")
				$width = 600;
			elseif ($val == "dateTime")
				$width = 120;
			?>
			<th width="<?= $width ?>"><a href="?<?= $get . 'orderby=' . $val ?>"><?= $val ?></a></th>
			<?php
		}
		?>
		<th width="50">Action</th>
	</tr>
	<?php
	$orderby = 'id';
	if (isset($_GET['orderby']))
		$orderby = mysqli_escape_string($mysqli, $_GET['orderby']);

	$query = 'SELECT * FROM aiseminar ORDER BY ' . $orderby;
	$query = ($orderby == 'id' || $orderby == 'vis') ? $query . ' DESC ' : $query . ' ASC ';
	//if (isset($limit)) $query = 'LIMIT '.$limit;
	$res = mysqli_query($mysqli, $query);
	if ($res) {
		while ($row = mysqli_fetch_assoc($res)) {
			?>
			<tr>
				<?php
				foreach ($aiSeminarFields as $val) {
					?>
					<td><?php echo ($val != "abstract") ? $row[$val] : mb_substr($row[$val], 0, 100) . "..." ?></td>
					<?php
				}
				?>
				<td>
					<a onclick="editEntry(<?= $row['id'] ?>)">
						<img src="images/edit.png" width="18" height="18" alt="edit"/>
					</a>
					<a onclick="setTask('deleteSeminar',<?= $row['id'] ?>)">
						<img src="images/delete.png" width="18" height="18" alt="delete"/>
					</a>					
				</td>
			</tr>
			<?php
		}
	}
	?>
</table>
