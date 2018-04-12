<?php
/*
	If the person's home we are trying to get is the Current User's page
	Then and only then will we load in our Friend's skits as well as their own
*/
if($_SESSION['user_ID'] == $id_to_get){

	//Add in some constants so we don't have to query the DB everytime we add in a skit
	//only when it is a different person than ourself
	$thisUsername = $username;
	$thisProfilePic = $profile_pic;
	$thisUserID = strval($_SESSION['user_ID']);

	//Get the skit data
	$url = "http://localhost:61234/getSkits?ids=";
	$url = $url . $friends . ",1";
	$skitData = file_get_contents($url);
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $skitData) as $line){

		//If the line of data is empty just leave the for loop because we have reached illegal data.
		if(strlen($line) == 0)
			break;
		$replyIDList = explode("|", $line);
		$replyList = explode(",", $replyIDList[1]);
		$line_arr = explode(",", $replyIDList[0]);
		$skitOwner = $line_arr[0];

		//If the owner of this skit we are loading is not the current user
		//We will need to query for some basic information about it
		if($skitOwner != $thisUserID){
			$skitUsername = "";
			$skitProfilePic = "";
			$stmt = $conn->prepare("SELECT username, profile_pic  FROM Users WHERE userid = ?;");
			$stmt->bind_param("i", $skitOwner);

			if(!$stmt->execute()){
				print "Error in executing command";
			}

			$stmt->bind_result($username, $profile_pic);
			$stmt->fetch();
			if(isset($userid)){
				die("Error setting username: username already being used<br>");
			}

			$stmt->close();
		} else {

			//Move our data back into the variables
			$profile_pic = $thisProfilePic;
			$username = $thisUsername;
		}

		?>
		<div id="post" class="container-fluid">
			<div id="personalBanner">
				<div id="bannerData">
					<img id="postPic" src="<?=$profile_pic?>" />
					<p id="postusername"><strong><?=$username?></strong></p>
				</div>
			</div>
			<div id="data">
				<p id="postContent">
					<?=$line_arr[1]?>
				</p>
				<div id="personalPostData">
					<div id="personalPostComment">
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
					</div>
					<?php
						/*
							If this my skit, then I can delete it.
						*/
						if($_SESSION['user_ID'] == $skitOwner){
							echo "
							<div id=\"deleteButtonDiv\">
								<form action=\"deleteSkit.php\" method=\"post\">
									<input type=\"hidden\" name=\"token\" value=\"" . hash_hmac('sha256', $randomString, $token) . "\">
									<input type=\"hidden\" name=\"skitID\" value=\"$line_arr[3]\">
									<button type=\"submit\" id=\"deleteButton\">Delete</button>
								</form>
							</div>";
						} else {
							?>
							<div id="comment">
								<form action="addComment.php" method="post">
									<input type="text" name="commentContent">
									<button type="submit" id="addComment">Add Comment</button>
								</form>
							</div>
					<?php
						}
					?>
				</div>
			</div>
		</div>
	<?php
	}
} else {
	//We are not on our own page so we only want to see that user's posts
	//Not their friends or our friends or our posts
	$url = "http://localhost:61234/getSkits?ids=";
	$url = $url . $id_to_get;
	$skitData = file_get_contents($url);
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $skitData) as $line){
		if(strlen($line) == 0)
			break;
		$line_arr = explode(",", $line);
		?>
		<div id="post" class="container-fluid">
			<div id="personalBanner">
				<div id="bannerData">
					<img id="postPic" src="<?=$profile_pic?>" />
					<p id="postusername"><strong><?=$username?></strong></p>
				</div>
			</div>
			<div id="data">
				<p id="postContent">
					<?=$line_arr[1]?>
				</p>
				<div id="personalPostData">
					<div id="personalPostComment">
						<p id="commentCount">1</p>
						<button type="button" id="commentButton"><img id="personalDataImg" src="../img/chat.svg"></button>
					</div>

					<?php
						if($_SESSION['user_ID'] == $id_to_get){
							echo "
							<div id=\"deleteButtonDiv\">
								<form action=\"deleteSkit.php\" method=\"post\">
									<input type=\"hidden\" name=\"token\" value=\"" . hash_hmac('sha256', $randomString, $token) . "\">
									<input type=\"hidden\" name=\"skitID\" value=\"$line_arr[3]\">
									<button type=\"submit\" id=\"deleteButton\">Delete</button>
								</form>
							</div>";
						}
					?>
				</div>
			</div>
		</div>
	<?php
	}
}
?>