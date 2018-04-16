<?php
	session_start();
	$calc = hash_hmac('sha256', $_SESSION['randomString'], $_SESSION['token']);
	if(!hash_equals($calc, $_POST['token'])){
		die("ERROR IN TOKEN");
	}

	$user_id = strip_tags($_SESSION['user_ID']);
	$skitID = strip_tags($_POST['skitID']);
	$data = array('user_id' => $user_id, 'skitID' => $skitID);
	$options = array(
		'http' => array(
			'header' => "Content-type: application/x-www-form-urlencoded\r\n",
			'method' => "POST",
			'content' => http_build_query($data)
		)
	);

	$context = stream_context_create($options);
	$result = file_get_contents("http://serversetup_node_1:61234/deleteSkit", false, $context);

	if(strcmp($result, "Error deleting Skit.") == 0){
		die("Error removing skit.");
	}

	header("Location: http://localhost/?id=" . $user_id);
?>