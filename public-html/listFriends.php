<?php
session_start();
include_once("php/sqlConnect.php");
$id_to_get = 1;
if(isset($_GET['id'])){
	$id_to_get = $_GET['id'];
}
$id_to_get = strip_tags($id_to_get);
include_once('php/getUserData.php');

$_SESSION['token'] = bin2hex(random_bytes(32));
$_SESSION['randomString'] = bin2hex(random_bytes(32));

$randomString = $_SESSION['randomString'];
$token = $_SESSION['token'];

?>
<!DOCTYPE html>
<html>
<head>
	<title>Skitter</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/listFriends.css">
	<link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">
	<link rel="manifest" href="img/site.webmanifest">
	<link rel="mask-icon" href="img/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">
</head>
<body>
	<div class="container-fluid" id="mainContainer">
		<div class="container-fluid">
			<div id="blur">
				<div class="container-fluid" id="inputArea">
					<h2 id="settingsHeader">Settings</h2>
					<div id="field">
						<form action="settings.php" method="post" enctype="multipart/form-data">
							<p>Display Name:</p>
							<input type="text" id="displayName" placeholder="Enter Display Name" name="displayName">
							<p>Email:</p>
							<input type="text" id="email" placeholder="Enter Email Address" name="email">
							<p>Profile Pic:</p>
							<input type="file" name="fileToUpload" id="fileToUpload">
							<input type="hidden" name="token" value="<?= hash_hmac('sha256', $randomString, $token)?>">
							<button type="submit" id="submitButton"><span>Submit</span></button>
							<button type="button" id="exitButton"><span>Close</span></button>
						</form>
					</div>
				</div>
			</div>
			<div class="container-fluid" id="userBanner">
				<div id="skitterData">
					<button id="getSettings" type="button">Settings</button>
					<a href="/?id=1"><button id="goHome" type="button" >Home</button></a>
				</div>
				<div id="userData">
					<div id="usernamediv">
						<h2 id="username">
							<?=$_SESSION['username'];?>
						</h2>
						<br/>
						<h5 id="email">
							<?= $_SESSION['email'];?>
						</h5>
					</div>
					<div id="bannerProfPic">
						<img id="userProfilePic" src="<?= $profile_pic?>" />
					</div>
				</div>
			</div>

			<div class="container-fluid" id="friendsPosts">
				<div id="about">
					<img id="skitterLogo" src="img/bird.svg" />
					<h4 id="title">Friends' Posts</h4>
				</div>
				<?php
					$friends = "";
					$stmt = $conn->prepare("SELECT following  FROM Users WHERE userid = ?;");
					$stmt->bind_param("i", $_SESSION['user_ID']);

					if(!$stmt->execute()){
						print "Error in executing command";
					}

					$stmt->bind_result($friends);
					$stmt->fetch();
					if(isset($userid)){
						die("Error setting username: username already being used<br>");
					}

					$stmt->close();

					$url = "http://serversetup_node_1:61234/getSkits?ids=";
					$url = $url . $friends;
					$skitData = file_get_contents($url);
					$i = 0;
					$skits = preg_split("/((\r?\n)|(\r\n?))/", $skitData);
					while($i < 4){
						$line = $skits[$i];
						if(strlen($line) == 0)
							break;
						$line_arr = explode(",", $line);
						$skitOwner = $line_arr[0];

						$skitUsername = "";
						$skitProfilePic = "";
						$stmt = $conn->prepare("SELECT username, profile_pic  FROM Users WHERE userid = ?;");
						$stmt->bind_param("i", $skitOwner);

						if(!$stmt->execute()){
							print "Error in executing command";
						}

						$stmt->bind_result($skitUsername, $skitProfilePic);
						$stmt->fetch();
						$stmt->close();
				?>
					<div id="friendPost" class="container">
						<div id="banner">
							<img id="friendProfilePic" src="<?=$skitProfilePic?>" />
							<h5><?=$skitUsername?></h5>
						</div>
						<div id="content">
							<p id="postContent">
								<?=$line_arr[1]?>
							</p>
							<div id="postData">
								<div id="likes">
									<p id="count"><?=$line_arr[2]?></p>
									<button type="button" id="likeButton"><img id="dataImg" src="img/like.svg" /></button>
								</div>
								<div id="comments">
									<p id="count">1</p>
									<button type="button" id="commentButton"><img id="dataImg" src="img/chat.svg" /></button>
								</div>
							</div>
						</div>
					</div>
				<?php
						$i = $i + 1;
					}
				?>
				<div id="seeMoreButton">
					<a href="listFriends.php"><button type="button" id="viewMoreButton">Friends</button></a>
				</div>
			</div>

			<div id="userPosts">
				<?php
					if(!isset($_POST['query']))
						echo "<div id=\"title\"><h1>Friend List</h1></div>";
					else
						echo "<div id=\"title\"><h1>Search Results</h1></div>";
				?>
				<div id="searchInput">
					<form action="listFriends.php" method="post">
						<input type="text" id="query" placeholder="Looking for someone?" name="query">
						<button type="submit" id="submitButton">Submit</button>
					</form>
				</div>
				<div id="friends" class="container-fluid">
					<?php
						if(isset($_POST['query'])){
							$url = "http://serversetup_flask_1:5000/searchUsers?query=";
							$searchTerm = $_POST['query'];
							$fullURL = $url . $searchTerm;
							$friends = file_get_contents($fullURL);
						}

						$friends = explode(",", $friends);
						foreach ($friends as &$friendNum) {
							$friendNum = trim($friendNum);
							$stmt = $conn->prepare("SELECT username, profile_pic  FROM Users WHERE userid = ?;");
							$stmt->bind_param("i", $friendNum);
							if(!$stmt->execute()){
								print "Error in executing command";
							}

							$stmt->bind_result($username, $profile_pic);
							$stmt->fetch();

							if($username !== NULL){
								echo "<a href=\"/?id=$friendNum\"><div id=\"friend\" class=\"container-fluid\">
								<img id=\"friendPic\" src=\"$profile_pic\" />
								<div id=\"nameAndAdd\">
								<p id=\"friendUsername\"><strong>$username</strong></p>
								<form action=\"php/addRemove.php\" method=\"post\">
								<input type=\"hidden\" value=\"$friendNum\" name=\"addRemoveID\">
								<input type=\"hidden\" value=" . hash_hmac('sha256', $randomString, $token) . " name=\"token\">
								<button type=\"submit\" id=\"addRemoveButton\">Add/Remove as Friend</button>
								</form>
								</div>
								</div></a>";
							}
							$stmt->close();
						}
					?>
				</div>
			</div>
		</div>
		<script src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/home.js"></script>
	</body>
	</html>