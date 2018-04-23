<?php
	//Authenticates the user and then starts the SQL connection.
	session_start();
	$url = "http://serversetup_auth_1:8080/Skitter/isAuthenticated?sessID=" . $_SESSION['user_ID'];
	$auth = file_get_contents($url);

	if(strcmp($auth, "Fail") == 0){
		die("Authentication Failure");
	}

	$servername = 'serversetup_mysql_1';
	$dbuname = 'root';
	$dbpass = 'root';
	$dbname = 'Skitter';
	$conn = new mysqli($servername, $dbuname, $dbpass, $dbname);

	if($conn->connect_error){
		die("Connection failed sorry");
	}
?>