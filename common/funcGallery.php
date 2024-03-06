<?php 

function listDirRec($dirPath) {
	$fileList = array();
	$counter = 1;
	if (file_exists($dirPath)) {
		if ($handle = opendir($dirPath)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					if (is_dir("$dirPath/$file")) {
						$fileList[$file] = listDirRec("files/".$file);
					} else {
						$fileList[$counter] = $file;
						$counter++;
					}
				}
			}
			closedir($handle);
		} else
			echo "error opening file";
	}
	return $fileList;
}

function listDirDir($dirPath) {
	$dirList = array();
	$counter = 1;
	if (file_exists($dirPath)) {
		if ($handle = opendir($dirPath)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					if (is_dir("$dirPath/$file")) {
						$dirList[$counter] = $file;
						$counter++;
					}
				}
			}
			closedir($handle);
		} else
			echo "error opening file";
	}
	return $dirList;
}
?>

