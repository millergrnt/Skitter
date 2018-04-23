<?php

$url = 'http://serversetup_auth_1:8080/Skitter/LoginServlet?user=' . $_POST['user'] . '&pwd=' . $_POST['pwd'];
$checkAuth = file_get_contents($url);
$checkAuth = trim($checkAuth);
if(is_numeric($checkAuth)){
	session_start();
	$_SESSION['user_ID'] = $checkAuth;
	header("Location: http://localhost/home.php?id=" . $_SESSION['user_ID']);
}

die("Authentication failure");

?>