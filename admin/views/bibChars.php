<?php
	$data = array();
    $pubId = -1;
    if (isset($_POST['task']) && $_POST['task'] == "invokeEdit") {
	  $pubId = intval($_POST['id']);
    } elseif (isset($_GET['id'])) {
	  $pubId = intval($_GET['id']);
    }
	$result = mysql_query('SELECT * FROM bibtex_chars WHERE id = ' . $pubId);
	if ($result) {
		$data = mysql_fetch_array($result);
		mysql_free_result($result);
    } 
?>
<div id="bibchars">
	<fieldset>
		<input type="text" name="char" id="char" value="<?php if (!(empty($data['char']))) echo $data['char'] ?>"/>
		<input type="text" name="bibcode" id="bibcode" value="<?php echo (!(empty($data['bibcode']))) ? $data['bibcode'] : "{\\\\\}" ?>"/>
<?php
	if ($pubId > -1) {
?>
		<input type="button" value="Edit" class="sbutton" onclick="setTask('editBibChar',<?= $pubId ?>)"/>
		<input type="button" value="Cancel" class="sbutton" onclick="setTask('clearTask')"/>		
<?php
	} else {
?>	
		<input type="button" value="Add" class="sbutton" onclick="setTask('newBibChar')"/>		
<?php		
	}
?>		
		[<strong>Hint</strong>: to process correctly, 3 backslashes are used instead of 1]
	</fieldset>
	<br/>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th>Character</th>
			<th>BibCode</th>
			<th>Actions</th>		
		</tr>
		<?php
		$query = 'SELECT * FROM bibtex_chars';
		$res = mysql_query($query);
		$c = 1;
		if ($res) {
			while ($row = mysql_fetch_assoc($res)) {
				if ($c >= $bibtablesize) {
					?>
			</table>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<th>Character</th>
					<th>BibCode</th>
					<th>Actions</th>
				</tr>
				<?php
					$c = 1;
				}
				?>
				<tr>
					<td><?php echo $row['char']; ?></td>
					<td><?php echo $row['bibcode']; ?></td>
					<td>
						<a onclick="editEntry(<?= $row['id'] ?>)">
							<img src="images/edit.png" width="18" height="18" alt="edit"/>
						</a>
						<a onclick="setTask('deleteBibChar','<?=$row['id']?>')">
							<img src="images/delete.png" width="18" height="18" alt="delete"/>
						</a>
					</td>
				</tr>
		<?php
				$c++;
			}
		}
		?>
	</table>
</div>