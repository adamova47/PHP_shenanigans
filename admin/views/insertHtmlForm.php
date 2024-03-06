<?php htmlButtonsH2('novytext'); ?>
<br/>
<select id="linkPaper" name="linkPaper" class="link">
	<?php
	$q2 = "SELECT * FROM " . $publiTable . " t LEFT JOIN " . $userxpub . " rel ON rel.publication = t.id WHERE rel.user = " . $user['id'];
	$r2 = mysql_query($q2);
	if ($r2) {
	while ($row2 = mysql_fetch_assoc($r2)) {
	?>
	<option value="<?= $row2['id'] ?>"><?= $row2['name'] . " (id " . $row2['id'] . ")" ?></option>
	<?php
	}
	}
	?>
</select>
<input type="button" value="link paper" onclick="addLinkPaper('novytext')" class="addHTML"/> 
<select id="linkProject" name="linkProject" class="link">
	<?php
	$q3 = "SELECT * FROM " . $projTable . " t LEFT JOIN " . $userxproj . " rel ON rel.project = t.id WHERE rel.user = " . $user['id'];
	$r3 = mysql_query($q3);
	if ($r3) {
	while ($row3 = mysql_fetch_assoc($r3)) {
	?>
	<option value="<?= $row3['id'] ?>"><?= $row3['tag'] ?></option>
	<?php
	}
	}
	?>
</select>
<input type="button" value="link project" onclick="addLinkProject('novytext')" class="addHTML"/> 
<div class="clear"></div>
<div class="left">
	<select id="listTags[]" name="listTags[]" class="link" multiple="multiple" size="4">
		<option value="all">all</option>
		<option value="none">none</option>
		<?php
		$q3 = "SELECT * FROM " . $projTable;
		$r3 = mysql_query($q3);
		if ($r3) {
		while ($row3 = mysql_fetch_assoc($r3)) {
		?>
		<option value="<?= $row3['id'] ?>"><?= $row3['tag'] ?></option>
		<?php
		}
		}
		?>
	</select>	
</div>
<div class="left">
	<select id="listUsers[]" name="listUsers[]" class="link" multiple="multiple" size="4" class="left">
		<option value="all">all</option>
		<?php
		$q3 = "SELECT * FROM " . $userTable;
		$r3 = mysql_query($q3);
		if ($r3) {
		while ($row3 = mysql_fetch_assoc($r3)) {
		?>
		<option value="<?= $row3['id'] ?>"<?= ($user['id'] == $row3['id']) ? ' selected="selected"' : '' ?>><?= usersFullName($row3['id']) ?></option>
		<?php
		}
		}
		?>
	</select>
</div>
<div class="left">
	<label for="visibleCount">Visible entries:</label>
	<input type="input" value="2" id="visibleCount" name="visibleCount" class="inpsmall"/> 
	<br/>
	<input type="button" value="list publications" onclick="addPubList('novytext')" class="addHTML left"/> 
</div>
<div class="clear"></div>
