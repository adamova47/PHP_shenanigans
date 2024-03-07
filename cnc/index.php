<?php
include "conf.php";
include "../common/conf.php";
include "../common/db_func.php";
include "../common/func.php";

$message = "";
$mysqli = db_connect();

if ($mysqli instanceof mysqli) {
    include "main.php";
    mysqli_close($mysqli);
}