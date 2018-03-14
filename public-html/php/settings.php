<?php
	session_start();
	$servername = 'localhost';
	$dbuname = 'root';
	$dbpass = 'root';
	$dbname = 'Skitter';
	$conn = new mysqli($servername, $dbuname, $dbpass, $dbname);
	$userid = NULL;

	if($conn->connect_error){
		die("Connection failed sorry");
	}

	$username = $_POST['displayName'];
	$email = $_POST['email'];
	$file = $_POST['fileToUpload'];

	//Validate the post parameters, they will be NULL if they were not entered.
	$username = validateUsername($username);
	$email = validateEmail($email);
	$file = validateFile($file);

	//Cookie has passed inspection, time to actually execute updates
	if(isset($username)){
		$stmt = $conn->prepare("SELECT userid FROM Users WHERE username = ?;");
		$stmt->bind_param("s", $username);

		if(!$stmt->execute()){
			print "Error in executing command";
		}

		$stmt->bind_result($userid);
		$stmt->fetch();
		if(isset($userid)){
			die("Error setting username: username already being used<br>");
		}

		$stmt->close();

		$stmt = $conn->prepare("UPDATE Users SET username = ? WHERE userid = ?;");
		$stmt->bind_param("si", $username, $_SESSION['user_ID']);

		if(!$stmt->execute()){
			print "Error in executing command";
		}

		$stmt->close();
	}

	if(isset($email)){
		$stmt = $conn->prepare("UPDATE Users SET email = ? WHERE userid = ?;");
		$stmt->bind_param("si", $email, $_SESSION['user_ID']);

		if(!$stmt->execute()){
			print "Error in executing command";
		}

		$stmt->close();
	}

	if(isset($file)){
		$stmt = $conn->prepare("UPDATE Users SET profile_pic = ? WHERE userid = ?;");
		$stmt->bind_param("si", $file, $_SESSION['user_ID']);

		if(!$stmt->execute()){
			print "Error in executing command";
		}

		$stmt->close();
	}

	function validateUsername($unameUnsanitized){
		$unameSanitized = strip_tags($unameUnsanitized);
		if(strlen($unameSanitized) >= 1){
			return $unameSanitized;
		}
		$unameSanitized = NULL;
		return $unameSanitized;
	}

	function validateEmail($emailUnsanitized){
		$emailSanitized = strip_tags($emailUnsanitized);
		if(strlen($emailSanitized) >= 1){
			if(filter_var($emailSanitized, FILTER_VALIDATE_EMAIL)){
				return $emailSanitized;
			} else {
				die("Email is invalid<br>");
			}
		}

		$emailSanitized = NULL;
		return $fileSanitized;
	}

	function validateFile($fileUnsanitized){
		$fileSanitized = strip_tags($fileUnsanitized);
		if(strlen($fileSanitized) >= 1){
			$image = getimagesize($fileSanitized) ? true : false;

			if($image == true){
				return $fileSanitized;
			} else {
				die("File uploaded was not an image<br>");
			}
		}

		$fileSanitized = NULL;
		return $fileSanitized;
	}
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