<?php
	#THIS NEEDS TO BE FIXED WITH MYSQL
	$servername = '172.18.0.2';
	$dbuname = 'root';
	$dbpass = 'root';
	$dbname = 'Skitter';
	$conn = new mysqli($servername, $dbuname, $dbpass, $dbname);

	if($conn->connect_error){
		die("Connection failed sorry");
	}
?>