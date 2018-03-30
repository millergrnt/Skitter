<?php
session_start();
include_once("php/sqlConnect.php");
$id_to_get = 1;
if(isset($_GET['id'])){
	$id_to_get = $_GET['id'];
}
$id_to_get = strip_tags($id_to_get);
$username = "";
$email = "";
$profile_pic = "";

$stmt = $conn->prepare("SELECT username, email, profile_pic  FROM Users WHERE userid = ?;");
$stmt->bind_param("i", $id_to_get);

if(!$stmt->execute()){
	print "Error in executing command";
}

$stmt->bind_result($username, $email, $profile_pic);
$stmt->fetch();
if(isset($userid)){
	die("Error setting username: username already being used<br>");
}

$stmt->close();

$_SESSION['token'] = bin2hex(random_bytes(32));
$_SESSION['randomString'] = bin2hex(random_bytes(32));
$_SESSION['deleteToken'] = bin2hex(random_bytes(32));
$_SESSION['user_ID'] = $id_to_get;
$_SESSION['username'] = $username;
$_SESSION['email'] = $email;
$token = $_SESSION['token'];
$randomString = $_SESSION['randomString'];
$deleteToken = $_SESSION['deleteToken'];
?>

<!DOCTYPE html>
<html>
<head>
	<title>Skitter</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/home.css">
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
						<form action="php/settings.php" method="post" enctype="multipart/form-data">
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
				<div id="friendPost" class="container">
					<div id="banner">
						<img id="friendProfilePic" src="img/youre-going-to-have-a-bad-time.png" />
						<h5>Steve Cook</h5>
					</div>
					<div id="content">
						<p id="postContent">
							I found this amazing chest routine. Seriously no pump ever like this before
						</p>
						<div id="postData">
							<div id="likes">
								<p id="count">5</p>
								<button type="button" id="likeButton"><img id="dataImg" src="img/like.svg" /></button>
							</div>
							<div id="comments">
								<p id="count">1</p>
								<button type="button" id="commentButton"><img id="dataImg" src="img/chat.svg" /></button>
							</div>
						</div>
					</div>
				</div>
				<div id="friendPost" class="container">
					<div id="banner">
						<img id="friendProfilePic" src="img/youre-going-to-have-a-bad-time.png" />
						<h5>Katie Nolan</h5>
					</div>
					<div id="content">
						<p id="postContent">
							Just found out that someone at 115 chicken nuggets to out score the actual Nuggets, ouch...
						</p>
						<div id="postData">
							<div id="likes">
								<p id="count">5</p>
								<button type="button" id="likeButton"><img id="dataImg" src="img/like.svg" /></button>
							</div>
							<div id="comments">
								<p id="count">1</p>
								<button type="button" id="commentButton"><img id="dataImg" src="img/chat.svg" /></button>
							</div>
						</div>
					</div>
				</div>
				<div id="friendPost" class="container">
					<div id="banner">
						<img id="friendProfilePic" src="img/youre-going-to-have-a-bad-time.png" />
						<h5>Jeff Skinner</h5>
					</div>
					<div id="content">
						<p id="postContent">
							My man Eric had a nasty one tonight, bar down baby!!!
						</p>
						<div id="postData">
							<div id="likes">
								<p id="count">5</p>
								<button type="button" id="likeButton"><img id="dataImg" src="img/like.svg" /></button>
							</div>
							<div id="comments">
								<p id="count">1</p>
								<button type="button" id="commentButton"><img id="dataImg" src="img/chat.svg" /></button>
							</div>
						</div>
					</div>
				</div>
				<div id="friendPost" class="container">
					<div id="banner">
						<img id="friendProfilePic" src="img/youre-going-to-have-a-bad-time.png" />
						<h5>Lauren Hart</h5>
					</div>
					<div id="content">
						<p id="postContent">
							Rocking crowd tn at the WFC!! Let's go Flyers!
						</p>
						<div id="postData">
							<div id="likes">
								<p id="count">5</p>
								<button type="button" id="likeButton"><img id="dataImg" src="img/like.svg" /></button>
							</div>
							<div id="comments">
								<p id="count">1</p>
								<button type="button" id="commentButton"><img id="dataImg" src="img/chat.svg" /></button>
							</div>
						</div>
					</div>
				</div>
				<div id="seeMoreButton">
					<a href="friendPosts.html"><button type="button" id="viewMoreButton">View More</button></a>
				</div>
			</div>

			<div class="container-fluid" id="userPosts">
				<div class="container-fluid" id="addPost">
					<form action="php/addSkit.php" method="post">
						<p>What are you thinking?</p>
						<input type="text" id="skitContent" placeholder="It's a nice day" name="skitContent" maxlength="140">
						<button type="submit" id="submitButton"><span>Submit</span></button>
					</form>
				</div>
				<?php
				$skitData = file_get_contents("http://localhost:61234/getSkits?id=1");
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
								<div id="personalPostlikes">
									<p id="likeCount"><?=$line_arr[2]?></p>
									<button type="button" id="likeButton"><img id="personalDataImg" src="img/like.svg"></button>
								</div>
								<div id="personalPostComment">
									<p id="commentCount">1</p>
									<button type="button" id="commentButton"><img id="personalDataImg" src="img/chat.svg"></button>
								</div>
								<div id="deleteButtonDiv">
									<form action="php/deleteSkit.php" method="post">
										<input type="hidden" name="token" value="<?= hash_hmac('sha256', $randomString, $token)?>">
										<input type="hidden" name="skitID" value="<?= $line_arr[3]?>">
										<button type="submit" id="deleteButton">Delete</button>
									</form>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				?>
				<div id="credits">
					<div>Icons made by <a href="https://www.flaticon.com/authors/smashicons" title="Smashicons">Smashicons</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
					<div>Icons made by <a href="https://www.flaticon.com/authors/pixel-buddha" title="Pixel Buddha">Pixel Buddha</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
				</div>
			</div>
		</div>
		<script src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/home.js"></script>
	</body>
	</html>