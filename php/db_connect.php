<?php
function db_connect() {

	static $mysqli;

	// check if already connected
	if (!isset($mysqli)) {
		// get db info
		$config = parse_ini_file('config.ini');

		// attempt db connection
		$mysqli = new mysqli('localhost',$config['username'],$config['password'],$config['dbname']);
	}

	// handle failure
	if ($mysqli->connect_errno) {
    	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}

	return $mysqli;
}
?>