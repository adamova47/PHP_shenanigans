<?php
if (function_exists('mysql_set_charset') === false) {
	/**
	* Sets the client character set.
	*
	* Note: This function requires MySQL 5.0.7 or later.
	*
	* @see http://www.php.net/mysql-set-charset
	* @param string $charset A valid character set name
	* @param resource $link_identifier The MySQL connection
	* @return TRUE on success or FALSE on failure
	*/
	function mysql_set_charset($charset, $link_identifier = null){
		if ($link_identifier == null) {
			return mysql_query('SET NAMES "'.$charset.'"');
		} else {
			return mysql_query('SET NAMES "'.$charset.'"', $link_identifier);
		}
	}
}

function db_connect() {
	global $database_host, $database_user, $database_pass, $database_name;
	$con = mysql_connect($database_host,$database_user,$database_pass);
	if (!$con) {
		echo "\n".'<br/>Could not connect: ' . mysql_error();
		return false;
	}
	mysql_set_charset('utf8');
	mysql_select_db($database_name);
	return true;
}
?>
