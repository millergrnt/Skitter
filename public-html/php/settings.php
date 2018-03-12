<?php
	$servername = 'localhost';
	$dbuname = 'root';
	$dbpass = 'root';
	$dbname = 'Skitter';
	$conn = new mysqli($servername, $dbuname, $dbpass, $dbname);

	if($conn->connect_error){
		die("Connection failed sorry");
	}

	$username = $_POST['displayName'];
	$email = $_POST['email'];
	$file = $_POST['fileToUpload'];

	$username = validateUsername($username);
	$email = validateEmail($email);
	$file = validateFile($file);

	//Let us assume the email is invalid, guilty until proved innocent
	$emailValid = false;

	function validateUsername($unameUnsanitized){
		$unameSanitized = strip_tags($unameUnsanitized);
		if(strlen($unameSanitized) < 1){
			print "Not doing anything with current username<br>";
		} else {
			print $unameSanitized . "<br>";
		}
	}

	function validateEmail($emailUnsanitized){
		$emailSanitized = strip_tags($emailUnsanitized);
		if(strlen($emailSanitized) < 1){
			print "Not doing anything with current email<br>";
		} else {

			if(checkdnsrr($emailSanitized, 'MX')){
				$emailValid = true;
				print "Email is valid: " . $emailSanitized . "<br";
			}
			print $emailSanitized . "<br>";
		}
	}

	function validateFile($fileUnsanitized){
		$fileSanitized = strip_tags($fileUnsanitized);
		if(strlen($fileSanitized) < 1){
			print "Not doing anything with current profile pic<br>";
		} else {
			$image = getimagesize($fileSanitized) ? true : false;

			if($image == true){
				print $fileSanitized . "<br>";
			} else {
				print "<br>File uploaded was not an image<br>";
			}
		}
	}

	$stmt = $conn->prepare("SELECT * FROM Users WHERE username = (?);");
	$stmt->bind_param("s", $username);

	$result = mysql_query($stmt);

	if(mysql_fetch_array($result) !== false)
		die('Username alread taken');



?>
<!DOCTYPE html>
<html>
	<head>
		<title>Skitter</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/home.css">
		<link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">
		<link rel="manifest" href="img/site.webmanifest">
		<link rel="mask-icon" href="img/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="theme-color" content="#ffffff">
	</head>
	<body>

	</body>
</html>