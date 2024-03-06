<?php
	include $_SERVER['DOCUMENT_ROOT']."/common/conf.php";
	include $_SERVER['DOCUMENT_ROOT']."/common/db_func.php";
	include $_SERVER['DOCUMENT_ROOT']."/common/func.php";
	include "myconf.php";    
   
	if (db_connect()) {
		$uid = 0;
            $q = "SELECT id FROM ".$userTable." WHERE username = '".mysql_escape_string($username)."'";
            $r_userid = mysql_query($q);
            if ($r_userid) {
                    $uid = mysql_result($r_userid,0,"id");
                    mysql_free_result($r_userid);
            }
           $user_full_name = usersFullName($uid);
            include $_SERVER['DOCUMENT_ROOT']."/common/usermain.php";
            mysql_close();
	}
?>
