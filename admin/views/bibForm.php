<?php
//id`, `type`, `address`, `author`, `booktitle`, `edition`, `editor`, `institution`, 
//`journal`, `month`, `note`, `number`, `organization`, `pages`, `publisher`, `school`, 
//`series`, `title`, `volume`, `year`   
/////////// bibtex data form ////////////////////////////////	
?>

<?php
/*
 * @inherited params: $id, $task, $data, $bibfieldsSorted, $bibTypes
 */
?>

<p class="hr"><strong>bibtex</strong></p>

<?php
if ($task == "editLatex") {
	?>
	<textarea name="latexarea" id="latexarea" rows="10" cols="65" class="smallarea"><?php
	if (!(empty($data))) {
		echo "@" . $data['ptype'] . "{" . $data['name'] . ",\n";

		foreach ($bibfieldsSorted as $val) {
			if ($val == "id" || $val == "vis" || $val == "ptype")
				;
			elseif ($data[$val] != "")
				echo $val . ' = {' . (($val == "title") ? "{" : "") . encodeBib($data[$val]) .
				(($val == "title") ? "}" : "") . '},' . "\n";
		}
		echo "}";
	}
	?></textarea>
	<?php
} else {
	?>
	<div class="box">
		<label for="ptype">type: </label>
		<select name="ptype" id="ptype">
			<?php
			foreach ($bibTypes as $val) {
				?>
				<option value="<?= $val ?>"<?= (!(empty($data)) && $data['ptype'] == $val) ? ' selected="selected"' : ''; ?>><?= $val ?></option>
				<?php
			}
			?>
		</select>
		<br/><br/>
		<?php
		$bibTypesAdv = array(
			"base" => array(
				array('name', 'author', 'title', 'year'),
				array('pages', 'month', 'note')
			),
			"+ @article" => array(
				array('journal'),
				array('volume', 'number'),
			),
			"+ @book, @inbook" => array(
				array('editor', 'publisher'),
				array('series', 'address', 'edition'),
			),
			"+ @conference, @incollection, @inproceedings" => array(
				array('booktitle'),
				array('organization'),
			),
			"+ @mastersthesis, @phdthesis" => array(
				array('school'),
			),
			"+ @techreport" => array(
				array('institution')
			),
		);
		foreach ($bibTypesAdv as $type => $value) {
			if (strstr($type, "book") != false) {
				?>
			</div>
			<div class="box">				
				<?php
			}
			if ($type != "base") {
				?>
				<p class="hr"><?= $type ?></p>
				<?php
			}
			foreach ($value[0] as $val) {
				?>
				<label for="<?= $val ?>"><?= $val ?>*:</label>
				<input type="text" value="<?= (!(empty($data))) ? $data[$val] : "" ?>" name="<?= $val ?>" id="<?= $val ?>"/><br />				
				<?php
			}
			if (sizeof($value) > 1) {
				foreach ($value[1] as $val) {
					?>
					<label for="<?= $val ?>" class="gray"><?= $val ?>: </label>
					<input type="text" value="<?= (!(empty($data))) ? $data[$val] : "" ?>" name="<?= $val ?>" id="<?= $val ?>"/><br />				
					<?php
				}
			}
		}
		?>
	</div>
	<?php
}
?>
