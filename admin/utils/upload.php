<?php

if (isset($_POST['formid']) && ($_POST['formid'] == $_SESSION['formid'])) {
	if (isset($_POST['task']) && (isset($_GET['part']) || isset($_GET['action']))) {

		$_SESSION['formid'] = $_SESSION['formid'] + 1;

		$task = $_POST['task'];

		if (isset($_GET['action'])) {
			if ($_GET['action'] == 'cnchome')
				$mainTable = "home_cnc";
			if ($_GET['action'] == 'meicogsci')
				$mainTable = "home_meicogsci";
			if ($_GET['action'] == 'kuz2013')
				$mainTable = "home_kuzxiii";
		} else {
			$mainTable = $user['tablename'];
		}

//////////////////////////// CHANGE homepage text ////////////////////////////////////////////////			  
		if ($task == "changeMain") {
			
//			print_r($_POST);
			
			if (isset($_POST['pagepart'])) {

				$textfield = 'novytext';
				$hasLeft = (isset($_POST['hasleft'])) ? 1 : 0;
				$query = "UPDATE " . $mainTable . " SET text = '" . mysqli_escape_string($mysqli, $_POST[$textfield]) . "'";
				if ($mainTable == $user['tablename']) {
					$query = $query . ", `has-left` = " . $hasLeft;
				}
				$query = $query . " WHERE getname = '" . mysqli_escape_string($mysqli, $_POST['pagepart']) . "'";;
				$result = mysqli_query($mysqli, $query);

				if ($result)
					$message = "Text of part <q>" . $_POST['pagepart'] . "</q> has been changed successfully.";
				else
					$message = $errordbupload.mysqli_error($mysqli);
			} else
				$message = "Page part to change not set.";
		}

//////////////////////////// ADD Publication Entry ////////////////////////////////////////////////

		elseif ($task == "newEntry") {
			//if there are no same
			$same = false;
			$qd = "SELECT * FROM " . $publiTable . " WHERE title = '" . mysqli_real_escape_string($mysqli, trim($_POST['title'])) . "' AND year = '" . mysqli_real_escape_string($mysqli,trim($_POST['year'])) . "'";
			$r_dupl = mysqli_query($mysqli, $qd);
			if ($r_dupl) {
				if (mysqli_num_rows($r_dupl) != 0) {
					$same = true;
				}
			}
			echo $same;
			if (!($same)) {
				//add new entry to publiTable
				$vis = (isset($_POST['vis'])) ? 1 : 0;
				$query = "INSERT INTO " . $publiTable . " (" . $publiTableString . ") VALUES (null, " . $vis;
				foreach ($publiTableFields as $key) {
					if (isset($_POST[$key])) {
						if ($key == "id" || $key == "vis")
							;
						else
							$query = $query . ", '" . mysqli_real_escape_string($mysqli, trim($_POST[$key])) . "'";
					}
				}
				$query = $query . ")";

				if (mysqli_query($mysqli, $query)) {
					$newid = mysqli_insert_id($mysqli);
					$message = "New entry " . $newid . " has been added successfully.";

					if (isset($_POST['addprojects']) && ($_POST['addprojects'][0] != 0)) {
						$additems = array();
						foreach ($_POST['addprojects'] as $p) {
							array_push($additems, "(" . mysqli_real_escape_string($mysqli, $p) . "," . $newid . ")");
						}
						$addquery = "INSERT INTO " . $projxpub . " (project, publication) VALUES " . implode(",", $additems);
						$addresult = mysqli_query($mysqli, $addquery);
						if ($addresult) {
							;
						} else {
							//$message = $message."<br />Unable to add relations<br />".mysql_error()."<br />";
						}
					}

					if (isset($_POST['addusers'])) {
						$additems = array();
						foreach ($_POST['addusers'] as $u) {
							array_push($additems, "(" . mysqli_real_escape_string($mysqli, $u) . "," . $newid . ")");
						}
						$addquery = "INSERT INTO " . $userxpub . " (user, publication) VALUES " . implode(",", $additems);
						$addresult = mysqli_query($mysqli, $addquery) or trigger_error(mysqli_error($mysqli));
						if ($addresult) {
							;
						} else {
							//$message = $message."<br />Unable to add relations<br />".mysql_error()."<br />";
						}
					}
				}
				else
					$message = $errordbupload . mysqli_error($mysqli);
			}
			else
				$message = "Entry with title " . $_POST['title'] . " and " . $_POST['year'] . " already exists!  ";
		}

//////////////////////////// EDIT Publication Entry ////////////////////////////////////////////////

		elseif ($task == "editEntry") {
			if (isset($_POST['id'])) {
				$id = intval($_POST['id']);
				$vis = (isset($_POST['vis'])) ? 1 : 0;
				$query = "UPDATE " . $publiTable . " SET ";
				foreach ($publiTableFields as $key) {
					if ($key == "id")
						;
					elseif ($key == "vis")
						$query = $query . $key . " = '" . $vis . "', ";
					else
						$query = $query . $key . " = '" . mysqli_real_escape_string($mysqli, $_POST[$key]) . "', ";
				}
				$query = substr($query, 0, strlen($query) - 2);
				$query = $query . " WHERE id = " . $id;

				if (mysqli_query($mysqli, $query)) {
					$message = "Entry " . $id . " has been changed successfully.";

					if (isset($_POST['addprojects']) && ($_POST['addprojects'][0] != 0)) {
						$additems = array();
						foreach ($_POST['addprojects'] as $p) {
							array_push($additems, "(" . mysqli_real_escape_string($mysqli, $p) . "," . $id . ")");
						}
						$addquery = "INSERT INTO " . $projxpub . " (project, publication) VALUES " . implode(",", $additems);
						$addresult = mysqli_query($mysqli, $addquery) or trigger_error(mysqli_error($mysqli));
						if ($addresult) {
							;
						} else {
							//$message = $message."<br />Unable to add relations<br />".mysql_error()."<br />";
						}
					}

					if (isset($_POST['deleteprojects'])) {
						$delitems = array();
						foreach ($_POST['deleteprojects'] as $p) {
							array_push($delitems, mysqli_real_escape_string($mysqli, $p));
						}
						$delquery = "DELETE FROM " . $projxpub . " WHERE project IN (" . implode(",", $delitems) . ")";
						$delresult = mysqli_query($mysqli, $delquery) or trigger_error(mysqli_error($mysqli));
						if ($delresult) {
							;
						} else {
							//$message = $message."<br />Unable to delete relations<br />".mysql_error()."<br />";
						}
					}

					if (isset($_POST['addusers'])) {
						$additems = array();
						foreach ($_POST['addusers'] as $u) {
							array_push($additems, "(" . mysqli_real_escape_string($mysqli, $u) . "," . $id . ")");
						}
						$addquery = "INSERT INTO " . $userxpub . " (user, publication) VALUES " . implode(",", $additems);
						$addresult = mysqli_query($mysqli, $addquery) or trigger_error(mysqli_error($mysqli));
						if ($addresult) {
							;
						} else {
							//$message = $message."<br />Unable to add relations<br />".mysql_error()."<br />";
						}
					}

					if (isset($_POST['deleteusers'])) {
						$delitems = array();
						foreach ($_POST['deleteusers'] as $u) {
							array_push($delitems, mysqli_real_escape_string($mysqli, $u));
						}
						$delquery = "DELETE FROM " . $userxpub . " WHERE user IN (" . implode(",", $delitems) . ")";
						$delresult = mysqli_query($mysqli, $delquery) or trigger_error(mysqli_error($mysqli));
						if ($delresult) {
							;
						} else {
							//$message = $message."<br />Unable to delete relations<br />".mysql_error()."<br />";
						}
					}
				}
				else
					$message = $message . $errordbupload;
			}
		}

//////////////////////////// DELETE Publication Entry ////////////////////////////////////////////////		

		elseif ($task == "deleteEntry") {
			if (isset($_POST['id'])) {
				$id = mysqli_escape_string($mysqli, $_POST['id']);
				$query = "DELETE FROM " . $publiTable . " WHERE id = " . $id;
				if (mysqli_query($mysqli, $query)) {
					$message = "Entry " . $id . " has been deleted successfully.";
					//delete relations
					$q_proj = "DELETE FROM " . $projxpub . " WHERE publication = " . $id;
					if (mysqli_query($mysqli, $q_proj))
						;
					else
						$message = $message . " Error deleting project - publication relations";
					$q_users = "DELETE FROM " . $userxpub . " WHERE publication = " . $id;
					if (mysqli_query($mysqli, $q_proj))
						;
					else
						$message = $message . " Error deleting user - publication relations";
				}
				else
					$message = $errordbupload;
			}
		}

//////////////////////////// MAKE PUBLIC generic ////////////////////////////////////////////////

		elseif ($task == "makePublic") {
			if (isset($_POST['id']) && isset($_POST['tablename']) && isset($_POST['changeVis'])) {
				$id = intval($_POST['id']);
				$vis = mysqli_escape_string($mysqli, $_POST['changeVis']);
				$query = "UPDATE " . mysqli_real_escape_string($mysqli, $_POST['tablename']) . " SET vis= '" . $vis . "' WHERE id = " . $id;
				if (mysqli_query($mysqli, $query)) {
					$message = "Visibility of entry " . $id . " has been changed successfully.";
				} else {
					$message = $errordbupload . mysqli_error($mysqli);
				}
			}
		}

///////////////////////////// ADD Project ////////////////////////////////////////////////////////	
		elseif ($task == "newProject") {
			$vis = (isset($_POST['vis'])) ? 1 : 0;
			$query = "INSERT INTO " . $projTable . " (" . $projTableString . ") VALUES (null, ";
			foreach ($projTableFields as $key) {
				if (isset($_POST[$key])) {
					if ($key == "id" || $key == "vis")
						;
					else
						$query = $query . "'" . mysqli_real_escape_string($mysqli, $_POST[$key]) . "', ";
				}
			}
			$query = $query . " '" . $vis . "')";
			//echo $query;
			if (mysqli_query($mysqli, $query)) {
				$newid = mysqli_insert_id($mysqli);
				$message = "New entry " . $newid . " has been added successfully.";
				//add new project - user relation
				if (isset($_POST['addusers'])) {

					$additems = array();
					foreach ($_POST['addusers'] as $u) {
						array_push($additems, "(" . mysqli_real_escape_string($mysqli, $u) . "," . $newid . ")");
					}
					$addquery = "INSERT INTO " . $userxproj . " (user, project) VALUES " . implode(",", $additems);
					$addresult = mysqli_query($mysqli, $addquery) or trigger_error(mysqli_error($mysqli));
					if ($addresult) {
						;
					} else {
						//$message = $message."<br />Unable to add relations<br />".mysql_error()."<br />";
					}
				}
			}
			else
				$message = $errordbupload;
		}

///////////////////////////// EDIT Project ////////////////////////////////////////////////////////			

		elseif ($task == "editProject") {
			if (isset($_POST['id'])) {
				$id = intval($_POST['id']);
				$vis = (isset($_POST['vis'])) ? 1 : 0;
				$query = "UPDATE " . $projTable . " SET ";
				foreach ($projTableFields as $key) {
					if ($key == "id" || $key == "vis")
						;
					else
						$query = $query . $key . " = '" . mysqli_real_escape_string($mysqli, $_POST[$key]) . "', ";
				}
				$query = $query . "vis = '" . $vis . "' WHERE id = " . $id;
				//echo $query;

				$result = mysqli_query($mysqli, $query) or trigger_error(mysqli_error($mysqli));
				if ($result) {
					$message = "Entry " . $id . " has been changed successfully.";

					if (isset($_POST['addusers'])) {

						$additems = array();
						foreach ($_POST['addusers'] as $u) {
							array_push($additems, "(" . mysqli_real_escape_string($mysqli, $u) . "," . $id . ")");
						}
						$addquery = "INSERT INTO " . $userxproj . " (user, project) VALUES " . implode(",", $additems);
						$addresult = mysqli_query($mysqli, $addquery) or trigger_error(mysqli_error($mysqli));
						if ($addresult) {
							;
						} else {
							//$message = $message."<br />Unable to add relations<br />".mysql_error()."<br />";
						}
					}
					if (isset($_POST['deleteusers'])) {

						$delitems = array();
						foreach ($_POST['deleteusers'] as $u) {
							array_push($delitems, mysqli_real_escape_string($mysqli, $u));
						}
						$delquery = "DELETE FROM " . $userxproj . " WHERE user IN (" . implode(",", $delitems) . ")";
						$delresult = mysqli_query($mysqli, $delquery) or trigger_error(mysqli_error($mysqli));
						if ($delresult) {
							;
						} else {
							//$message = $message."<br />Unable to delete relations<br />".mysql_error()."<br />";
						}
					}
				}
				else
					$message = $errordbupload;
			}
		}

///////////////////////////// DELETE Project ////////////////////////////////////////////////////////	

		elseif ($task == "deleteProject") {
			if (isset($_POST['id'])) {
				$id = intval($_POST['id']);
				$query = "DELETE FROM " . $projTable . " WHERE id = " . $id;
				if (mysqli_query($mysqli, $query)) {
					$message = "Project " . $id . " has been deleted successfully.";
					//delete relations
					$q_publ = "DELETE FROM " . $projxpub . " WHERE project = " . $id;
					if (mysqli_query($mysqli, $q_publ))
						;
					else
						$message = $message . " Error deleting project - publications relations";
					$q_users = "DELETE FROM " . $userxproj . " WHERE project = " . $id;
					if (mysqli_query($mysqli, $q_users))
						;
					else
						$message = $message . " Error deleting project - users relations";
				}
				else
					$message = $errordbupload;
			}
		}
//////////////////////////// ADD Seminar ////////////////////////////////////////////////
		elseif ($task == "newSeminar") {
			$query = "INSERT INTO aiseminar (" . implode(', ', $aiSeminarFields) . ") VALUES (null";
			foreach ($aiSeminarFields as $key) {
				if (isset($_POST[$key])) {
					if ($key == "id")
						;
					elseif ($key == "datetime")
							$query = $query.", '".date('Y-m-d H:i:s', strtotime($_POST[$key]))."'";
					else
							$query = $query.", '".mysqli_real_escape_string($mysqli, trim($_POST[$key]))."'";
				}
			}
			$query = $query . ")";
			if (mysqli_query($mysqli, $query)) {
				$newid = mysqli_insert_id($mysqli);
				$message = "New entry " . $newid . " has been added successfully.";
			} else {
				$message = $errordbupload . '<br/>' . mysqli_error($mysqli);
			}
		}
//////////////////////////// EDIT Seminar ////////////////////////////////////////////////
		elseif ($task == "editSeminar") {

			if (isset($_POST['id'])) {
				$id = intval($_POST['id']);

				$query = "UPDATE aiseminar SET ";
				foreach ($aiSeminarFields as $key) {
					if ($key == "id")
						;
					elseif ($key == "datetime")
					$query = $query.$key." = '".date('Y-m-d H:i:s', strtotime($_POST[$key]))."', ";
					else
					$query = $query.$key." = '".mysqli_real_escape_string($mysqli, $_POST[$key])."', ";
				}
				$query = substr($query, 0, strlen($query) - 2);
				$query = $query . " WHERE id = " . $id;

				if (mysqli_query($mysqli, $query)) {
					$message = "Entry " . $id . " has been changed successfully.";
				}
				else
					$message = $errordbupload;
			}
		}
///////////////////////////// DELETE Seminar ////////////////////////////////////////////////////////	
		elseif ($task == "deleteSeminar") {
			if (isset($_POST['id'])) {
				$id = intval($_POST['id']);
				$query = "DELETE FROM aiseminar WHERE id = " . $id;
				if (mysqli_query($mysqli, $query)) {
					$message = "Entry " . $id . " has been deleted successfully.";
				}
				else
					$message = $errordbupload;
			}
		}
	//////////////////////////// ADD BibChar ////////////////////////////////////////////////
		elseif ($task == "newBibChar") {		
			$bibCode = $_POST['bibcode'];
			$query = "INSERT INTO bibtex_chars (`id`, `char`, `bibcode`) VALUES (null, '".$_POST['char']."', '".$bibCode."')";
			if (mysqli_query($mysqli, $query)) {
				$newid = mysqli_insert_id($mysqli);
				$message = "New entry " . $newid . " has been added successfully.";
			} else {
				$message = $errordbupload . '<br/>' . mysqli_error($mysqli);
			}
		}
	//////////////////////////// EDIT BibChar ////////////////////////////////////////////////
		elseif ($task == "editBibChar") {
			if (isset($_POST['id'])) {
				$id = intval($_POST['id']);
				$query = "UPDATE bibtex_chars SET `char` = '".$_POST['char']."', `bibcode` = '".$_POST['bibcode']."' WHERE id = ".$id;
				if (mysqli_query($mysqli, $query)) {
					$message = "Entry " . $id . " has been changed successfully.";
				}
				else
					$message = $errordbupload;
			}
		}
	///////////////////////////// DELETE BibChar ////////////////////////////////////////////////////////	
		elseif ($task == "deleteBibChar") {
			if (isset($_POST['id'])) {
				$id = intval($_POST['id']);
				$query = "DELETE FROM bibtex_chars WHERE id = ".$id;
				if (mysqli_query($mysqli, $query)) {
					$message = "Entry " . $id . " has been deleted successfully.";
				}
				else
					$message = $errordbupload;
			}
		}
	}
}
?>
