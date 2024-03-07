<?php
function db_connect()
{
    global $database_host, $database_user, $database_pass, $database_name;

    $con = mysqli_connect($database_host, $database_user, $database_pass, $database_name);

    if (!$con) {
        echo "\n" . '<br/>Could not connect: ' . mysqli_connect_error();
        return false;
    }

    mysqli_set_charset($con, 'utf8');
    return $con;
}

?>
