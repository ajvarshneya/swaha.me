<?php

	/*
	 * This php script validates URL passed in POST, and 
	 * retrieves its associated short URL from the database.
	 */

	// Imports
	if (!function_exists(db_connect)) include 'db_connect.php';
	if (!function_exists(base62_encode)) include 'base62.php';
	include 'get_id_hash.php';

	// Get URL from POST, remove http(s) from beginning
	$url = $_POST['url'];
	$url = preg_replace('#[h|H][t|T][t|T][p|P][s|S]?://#', '', $url);

	// Main body
	if (validate_url($url)) {

		// Connect to database
		$mysqli = db_connect();

		// Query to see if URL is in database

		// Prepare statement
		if (!($stmt = $mysqli->prepare("SELECT shorturl from urls where url=?"))) {
			die('Query failed.');
		}

		// Bind params, execute query, bind/fetch result, close stmt
		$stmt->bind_param('s', $url);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($surl);
		$stmt->fetch();

		 // If URL not in database, add it. Otherwise, get the existing short URL.
		if ($stmt->num_rows == 0) {
			// Case 1: URL not in database.

			$stmt->close();

			// Exit condition variable
			$surl_is_new = false;

			// Get last id
			$id = get_last_id($mysqli, $url); 

			// Find a new short url
			while ($surl_is_new == false) {

				// Get next id/hash it
				$id++; 
				$surl = get_id_hash($id);

				// Check if hash in database, if it's not then use it

				// Prepare statement
				if (!($stmt = $mysqli->prepare("SELECT id from urls where shorturl=?"))) {
					die('Query failed.');
				}

				// Bind params, execute query, bind/fetch result
				$stmt->bind_param('s', $surl);
				$stmt->execute();
				$stmt->store_result();
				$stmt->fetch();

				// Is it a new short URL?
				if ($stmt->num_rows == 0) {
					$surl_is_new = true;
				}

				$stmt->close();
			}	

			// Get timestamp
			$date = date("Y-m-d H:i:s", time());

			/*
			 * Add the URL to the database with its associated information.
			 */

			// Prepare statement
			if (!($stmt = $mysqli->prepare("INSERT into urls (id, url, shorturl, date) VALUES (?, ?, ?, ?)"))) {
				die('Query failed.');
			}

			// Bind params, execute query, close statement
			$stmt->bind_param('ssss', $id, $url, $surl, $date);
			$stmt->execute();
			$stmt->close();
		} else { 
			// Case 2: URL in database

			// Prepare statement
			if (!($stmt = $mysqli->prepare("SELECT shorturl from urls where url=?"))) {
				die('Query failed.');
			}

			// Bind params, execute query, bind/fetch result, close stmt
			$stmt->bind_param('s', $url);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($surl);
			$stmt->fetch();
			$stmt->close();
		}

		$url = 'http://swaha.me/' . $surl;


		$_POST["surl"] = $surl;

		echo json_encode($_POST);

	} else if ($url != "") {
		die('Invalid URL.');
	}

	/*
	 * This function returns the last id added to the database.
	 * @param mysqli - connection to mysql database
	 */
	function get_last_id($mysqli) {

		// Prepare statement
		if (!($stmt = $mysqli->prepare("SELECT id from urls where id=(SELECT max(id) from urls)"))) {
			die('Query failed.');
		}

		// Inject URL, execute query, bind/fetch result, close stmt
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($id);
		$stmt->fetch();
		$stmt->close();

		return $id;
	}

	/*
	 * This function determines whether a url is valid.
	 * @param url - url under evaluation
	 */
	function validate_url($url) {

		// Parse url and set scheme
		if ($parts = parse_url($url)) {
		   if (!isset($parts["scheme"]))
		   {
		       $url = "http://$url";
		   }
		}

		// Check if the URL is swaha.me ...
		if (preg_match('@(www.)?([s|S][w|W][a|A][h|H][a|A])([.][m|M][e|E])(.*)@', $url)) {
			exit();
		}

		// Validate
		return (filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED) !== false);
	}
?>