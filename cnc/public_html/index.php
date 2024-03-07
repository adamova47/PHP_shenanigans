<?php
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf.php";
include $_SERVER['DOCUMENT_ROOT'] . "/common/db_func.php";
include $_SERVER['DOCUMENT_ROOT'] . "/common/func.php";
include "myconf.php";

$mysqli = db_connect();
$uid = 0;
$q = "SELECT id FROM $userTable WHERE username = '".mysqli_real_escape_string($mysqli, $username)."'";
$r_userid = mysqli_query($mysqli, $q);

if ($r_userid) {
    $row = mysqli_fetch_assoc($r_userid);
    $uid = $row['id'];
    mysqli_free_result($r_userid);
}

$user_full_name = usersFullName($uid);
include $_SERVER['DOCUMENT_ROOT'] . "/common/usermain.php";
mysqli_close($mysqli);