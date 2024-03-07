<?php
/*
 * @inherited params: $id,
 */
$relatedusers = array();
$r_relusers = mysqli_query($mysqli, "SELECT * FROM " . $userTable . " AS u," . $userxpub . " AS rel WHERE u.id = rel.user AND rel.publication = " . $id);
if ($r_relusers) {
	while ($row2 = mysqli_fetch_assoc($r_relusers)) {
		array_push($relatedusers, '<strong>' . $row2['username'] . '</strong>');
	}
	mysqli_free_result($r_relusers);
}
$relatedprojects = array();
$r_relprojects = mysqli_query($mysqli, "SELECT * FROM " . $projTable . " AS p," . $projxpub . " AS rel WHERE p.id = rel.project AND rel.publication = " . $id);
if ($r_relprojects) {
	while ($row2 = mysqli_fetch_assoc($r_relprojects)) {
		array_push($relatedprojects, '<strong>' . $row2['tag'] . '</strong>');
	}
	mysqli_free_result($r_relprojects);
}
if ($id > -1) {
	?>
	<p>
		users: <?= implode(", ", $relatedusers) ?> | tags: <?= implode(", ", $relatedprojects) ?> 
	</p>		
	<?php
}
	?>
<div class="box2">
	<label for="addprojects">add tags: </label>
	<select name="addprojects[]" id="addprojects[]" multiple="multiple" size="6">
		<option value="0" selected="selected">none</option>
		<?php echo projectsAsOptions(null); ?>
	</select>
</div>
<?php
if ($id > 0) {
	?>
	<div class="box2">
		<label for="deleteprojects">remove tags: </label>
		<select name="deleteprojects[]" id="deleteprojects[]" multiple="multiple" size="6">
			<option value="0" selected="selected">none</option>
			<?php echo projectsAsOptions(null); ?>
		</select>
	</div>
<?php } ?>
<div class="box2">
	<label for="addusers">add users: </label>
	<select name="addusers[]" id="addusers[]" multiple="multiple" size="6">
		<option value="0" <?= ($uId == -1) ? 'selected="selected"' : ''; ?>>none</option>
		<?php echo usersAsOptions($selusers); ?>
	</select>
</div>
<?php
if ($id > 0) {
	?>		
	<div class="box2">
		<label for="deleteusers">remove users: </label>
		<select name="deleteusers[]" id="deleteusers[]" multiple="multiple" size="6">
			<option value="0" selected="selected">none</option>
			<?php echo usersAsOptions(null); ?>
		</select>
	</div>
<?php }
?>