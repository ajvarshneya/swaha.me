<?php	
	if (!function_exists(db_connect)) include 'php/db_connect.php';
	include 'php/base62.php';
	include 'php/get_surl.php';

	// Get URL extension
	$surl = $_GET['u'];

	// If there was an extension, redirect
	if ($surl != "") {

		// connect to mysql database
		$mysqli = db_connect();

		// Prepare statement
		if (!($stmt = $mysqli->prepare("SELECT url from urls where shorturl=?"))) {
			die('Query failed.');
		}

		// Bind params, execute query, bind/fetch result, close stmt
		$stmt->bind_param('s', $surl);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($new_url);
		$stmt->fetch();
		$stmt->close();
		
		header("Location: http://$new_url");
		die();
	}
?>
<html>
<!-- favicon -->
	<head>
		<meta name="description" content="A URL shortening tool."/>
		<meta name="robots" content="nofollow, noarchive" />
		<meta name="google" content="notranslate" />
		<meta name="viewport" content="user-scalable=yes, initial-scale=1.0, maximum-scale=2.0, width=device-width" />
		<link rel="icon" type="image/png" href="images/av64.png">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link href='https://fonts.googleapis.com/css?family=Jura' rel='stylesheet' type='text/css'>	
	</head>
	<title>
		swaha
	</title>

	<body>
		<div class="centered">
			<div id="title-container">
				swaha &middot; URL shortener
			</div>
			<div id="form-container">
				<form action="php/get_surl.php" method="post" enctype="multipart/form-data" id="js-submit-url">
					<input type="text" id="url-form" name="url" placeholder="Enter a URL" autocomplete="off" autofocus>
				</form>
				<button id="js-copy-button">copy</button>
			</div>
	</body>

</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="javascript/get_url.js"></script>
<script src="javascript/copy_url.js"></script>



