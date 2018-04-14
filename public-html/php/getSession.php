<?php

$_COOKIE['sessionID'] = $session_to_get;
$stmt = $conn->prepare("SELECT userid FROM sessions WHERE sessionID = ?;");
$stmt->bind_param("s", $session_to_get);
if(!$stmt->execute()){
	die("Error in the server, sorry");
}

$stmt->bind_result($id_to_get);
$stmt->fetch();

$stmt->close();
?>