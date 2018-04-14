<?php

$username = "";
$email = "";
$profile_pic = "";
$friends = "";

$stmt = $conn->prepare("SELECT username, email, profile_pic, following  FROM Users WHERE userid = ?;");
$stmt->bind_param("i", $id_to_get);

if(!$stmt->execute()){
	die("Error in the server, sorry");
}

$stmt->bind_result($username, $email, $profile_pic, $friends);
$stmt->fetch();
if(isset($userid)){
	die("Error setting username: username already being used<br>");
}

$stmt->close();

?>