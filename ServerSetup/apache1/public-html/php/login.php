<?php

$url = 'http://serversetup_auth_1:8080/Skitter/LoginServlet?user=' . $_POST['user'] . '&pwd=' . $_POST['pwd'];
$checkAuth = file_get_contents($url);
if(strpos($checkAuth, "Either") != 0){
	$arr = explode(" ", $checkAuth);
	session_start();
	$_SESSION['user_ID'] = $arr;
	header("Location: http://localhost/home.php?id=" . $_SESSION['user_ID']);
}

die("Result" . var_dump($result));

?>