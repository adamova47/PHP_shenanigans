<?php

include "conf.php";
include "../common/conf.php";
include "../common/db_func.php";
include "../common/func.php";

$message = "";

if (db_connect()) {
    include "main.php";
    mysql_close();
}
?>
