<html>
	<head>
		<title>My App</title>
	</head>
	<body>
	<?php
		require __DIR__ . '/vendor/autoload.php';
		include('db.php');

		use XeroPHP\Application\PublicApplication;
		use XeroPHP\Remote\Request;
		use XeroPHP\Remote\URL;
		// Start a session for the oauth session storage
		session_start();

		unset($_SESSION['oauth']['token']);
		unset($_SESSION['oauth']['token_secret']);
		$_SESSION['oauth']['expires'] = null;
		
		/******* by raman dated('19-06-2020')*********************/
	 setcookie('companyName', FALSE, -1, '/');
	  setcookie('accessToken', FALSE, -1, '/');
		$dbc = new db();
	    $delete1 = $dbc->execute("DELETE  FROM  organisation_detail WHERE userRef ='" . $_COOKIE['userId']. "'");
		$delete2 = $dbc->execute("DELETE  FROM  token_detail WHERE userRef ='" . $_COOKIE['userId']. "'");
		/***************************************/

		 header("Location: xeroConnect.php");
	?>
	</body>
</html>
