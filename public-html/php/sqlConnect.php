<?php
	$servername = 'localhost';
	$dbuname = 'root';
	$dbpass = 'root';
	$dbname = 'Skitter';
	$conn = new mysqli($servername, $dbuname, $dbpass, $dbname);

	if($conn->connect_error){
		die("Connection failed sorry");
	}
?>