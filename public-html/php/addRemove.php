<?php
	session_start();
	include_once("sqlConnect.php");

	$userid = $_SESSION['user_ID'];
	if(!isset($_POST['addRemoveID'])){
		die("Not POST, fatal error");
	}

	$calc = hash_hmac('sha256', $_SESSION['randomString'], $_SESSION['token']);
	if(!hash_equals($calc, $_POST['token'])){
		die("ERROR IN TOKEN");
	}
	$idToAddRemove = $_POST['addRemoveID'];

	$friends = "";
	$stmt = $conn->prepare("SELECT following  FROM Users WHERE userid = ?;");
	$stmt->bind_param("i", $userid);
	if(!$stmt->execute()){
		print "Error in executing command";
	}

	$stmt->bind_result($friends);
	$stmt->fetch();

	$friends = explode(",", $friends);
	if(!in_array($idToAddRemove, $friends)){
		$url = "http://localhost:5000/addFriend?id=";
		$url = $url . $idToAddRemove . "&currID=" . $_SESSION['user_ID'];
		$add = file_get_contents($url);
		if(strcmp($add, "Error") == 0){
			die("Error Adding Friend");
		}
	} else {
		$url = "http://localhost:5000/removeFriend?id=";
		$url = $url . $idToAddRemove . "&currID=" . $_SESSION['user_ID'];
		$remove = file_get_contents($url);
		if(strcmp($remove, "Error") == 0){
			die("Error Removing Friend");
		}
	}

	$stmt->close();
	header("Location: http://grantimac.student.rit.edu/listFriends.php?id=" . $userid);
?>