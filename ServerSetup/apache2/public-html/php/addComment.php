<?php
session_start();
$user_id = strip_tags($_SESSION['user_ID']);
$content = strip_tags($_POST['commentContent'], "a");
$originalSkitID = strip_tags($_POST['originalSkitID']);
$content = urlencode($content);
$url = "http://172.18.0.7:3000/add_skit_reply/result?user_id=" . $user_id . "&content=" . $content . "&originalSkitID=" . $originalSkitID;

$result = file_get_contents($url);

if(strcmp($result, "Error creating Skit.") == 0){
	die("Error adding skit.");
}

header("Location: http://localhost/?id=" . $user_id);
?>