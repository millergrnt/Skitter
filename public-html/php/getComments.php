<?php
	if($replyList[0] != -1){
		foreach($replyList as $replyID){
			$url = "http://localhost:61234/getReply?id=";
			$url = $url . $replyID;
			$replyData = file_get_contents($url);
			$replyData = explode(",", $replyData);
			$stmt = $conn->prepare("SELECT username, profile_pic  FROM Users WHERE userid = ?;");
			$stmt->bind_param("i", $replyData[0]);

			if(!$stmt->execute()){
				print "Error in executing command";
			}

			$stmt->bind_result($username, $profile_pic);
			$stmt->fetch();
			if(isset($userid)){
				die("Error setting username: username already being used<br>");
			}

			$stmt->close();
?>
	<div class="comment">
		<img id="replyPic" src="<?=$profile_pic?>" />
		<p id="replyUsername"><strong><?=$username?></strong></p>
		<p id="replyContent"><?=$replyData[1]?></p>
	</div>
<?php
		} 
	}?>