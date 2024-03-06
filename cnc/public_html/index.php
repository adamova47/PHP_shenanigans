<?php
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf.php";
include $_SERVER['DOCUMENT_ROOT'] . "/common/db_func.php";
include $_SERVER['DOCUMENT_ROOT'] . "/common/func.php";
include "myconf.php";

// establish database connection
$mysqli = db_connect();

// checking whether $mysqli is an instance of the mysqli class -> successful connection
if ($mysqli instanceof mysqli) {
    $uid = 0; // variable to store the user ID

    // constructs a SQL query to get the user ID based on username
    $q = "SELECT id FROM $userTable WHERE username = '".mysqli_real_escape_string($mysqli, $username)."'";
    // execute the SQL query and store the result
    $r_userid = mysqli_query($mysqli, $q);

    // check whether the query execution was successful
    if ($r_userid) {
        // fetch the first row of the result as an array ??
        $row = mysqli_fetch_assoc($r_userid);
        // update $uid with the user ID from the database
        $uid = $row['id'];
        // free the memory associated with the result
        mysqli_free_result($r_userid);
    }

    // retrieves the full name of the user based on user ID
    $user_full_name = usersFullName($uid);

    // include user main file
    include $_SERVER['DOCUMENT_ROOT'] . "/common/usermain.php";

    // closes database connection
    mysqli_close($mysqli);
}