<?php
	session_start();
	$user_id = strip_tags($_SESSION['user_ID']);
	$content = strip_tags($_POST['skitContent'], "a");
	$data = array('user_id' => $user_id, 'content' => $content);
	$options = array(
		'http' => array(
			'header' => "Content-type: application/x-www-form-urlencoded\r\n",
			'method' => "POST",
			'content' => http_build_query($data)
		)
	);

	$context = stream_context_create($options);
	$result = file_get_contents("http://172.18.0.6:61234/addSkit", false, $context);

	if(strcmp($result, "Error creating Skit.") == 0){
		die("Error adding skit.");
	}

	header("Location: http://localhost/?id=" . $user_id);
?>