<?php
    session_start();
    include "../common/conf.php";
    include "../common/db_func.php";
    include "../common/func.php";
    include "utils/conf.php";
    include "utils/adminfunc.php";
     
    $message = "";
    $user = array();
	$mysqli = db_connect();
     
	if (!isset($_SESSION['formid'])) 
	    $_SESSION['formid'] = 0;
	
	//if (isset($newid)) echo $newid;

	if (isset($_SESSION['loggedin'])) {
		if ($_SESSION['loggedin'] == 1) {		
			if (isset($_SESSION['user']))
				$user = $_SESSION['user'];
			include $script_upload;
			include $view_admin;
		}
	}
	elseif (ISSET($_POST['name']) && ISSET($_POST['pass'])) {				
		//chceck username & pass with database
		$r_user = mysqli_query($mysqli, "SELECT * FROM ".$userTable." WHERE username = '".mysqli_real_escape_string($mysqli, $_POST['name'])."'".
							" AND password = '".sha1(mysqli_real_escape_string($mysqli, $_POST['pass']))."'");
		if ($r_user) {
			$user = mysqli_fetch_array($r_user);
			$_SESSION['user'] = $user;
			//echo $user;
			mysqli_free_result($r_user);
		}
		if (empty($user)) {
			$_SESSION['message'] = $errorlogin;
			include $view_login;
		}
		else {
			include $view_admin;
			$_SESSION['message'] = "";
			$_SESSION['loggedin'] = 1; 
		}
	} 
	else {
		include $view_login;
	}
	
	if (isset($_GET['action']) && $_GET['action'] == $get_logout) {
			session_destroy();
			header("Location: index.php");exit;
	} 
	// mysqli_close($mysqli);
?>
