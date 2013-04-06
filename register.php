<?php
include_once("scripts/check_user.php");
if($user_is_logged == true){
	header("location: index.php");
	exit();
}
if(isset($_POST['username'])){
    $username = preg_replace('#[^a-z0-9]#i', '', $_POST['username']);
    $email1 = strip_tags($_POST['email1']);
    $email2 = strip_tags($_POST['email2']);
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    // make sure no fields are blank /////
    if(trim($username) == "" || trim($email1) == "" || trim($pass1) == "" || trim($pass2) == ""){
        echo "Error: All fields are required. Please press back in your browser and try again.";
        $db = null;
        exit();
    }
    /// Make sure both email fields match /////
    if($email1 != $email2){
        echo "Your email fields do not match. Press back and try again";
        exit();
    }
    //// Make sure both password fields match ////
    else if($pass1 != $pass2){
        echo "Your password fields do not match. Press back and try again";
        exit();
    }
	if(!filter_var($email1, FILTER_VALIDATE_EMAIL)){
		echo "You have entered an invalid email. Press back and try again";
        exit();
	}
    //// create the hmac /////
    $hmac = hash_hmac('sha512', $pass1, file_get_contents('path/to/key.txt'));
    //// create random bytes for salt ////
    $bytes = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
    //// Create salt and replace + with . ////
    $salt = strtr(base64_encode($bytes), '+', '.');
    //// make sure our bcrypt hash is 22 characters which is the required length ////
    $salt = substr($salt, 0, 22);
    //// This is the hashed password to store in the db ////
    $bcrypt = crypt($hmac, '$2y$12$' . $salt);
    $token = md5($bcrypt);
	//// query to check if email is in the db already ////
	$stmt = $db->prepare("SELECT email FROM members WHERE email=:email1 LIMIT 1");
	$stmt->bindValue(':email1',$email1,PDO::PARAM_STR);
	try{
	$stmt->execute();
	$count = $stmt->rowCount();
	}
	catch(PDOException $e){
		echo $e->getMessage();
			$db = null;
			exit();
	}
	//// query to check if the username is in the db already ////
	$unameSQL = $db->prepare("SELECT username FROM members WHERE username=:username LIMIT 1");
	$unameSQL->bindValue('username',$username,PDO::PARAM_STR);
	try{
		$unameSQL->execute();
		$unCount = $unameSQL->rowCount();
	}
	catch(PDOException $e){
		echo $e->getMessage();
		$db = null;
		exit();
	}
	///Check if email is in the db already ////
	if($count > 0){
		echo "Sorry, that email is already in use in the system";
		$db = null;
		exit();
	}
	//// Check if username is in the db already ////
	if($unCount > 0){
		echo "Sorry, that username is already in use in the system";
		$db = null;
		exit();
	}
	try{
		$db->beginTransaction();
		$ipaddress = getenv('REMOTE_ADDR');
		$stmt2 = $db->prepare("INSERT INTO members (username, email, password, signup_date, ipaddress) VALUES (:username, :email1, :bcrypt, now(), :ipaddress)");
		$stmt2->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt2->bindParam(':email1',$email1,PDO::PARAM_STR);
		$stmt2->bindParam(':bcrypt',$bcrypt,PDO::PARAM_STR);
		$stmt2->bindParam(':ipaddress',$ipaddress,PDO::PARAM_INT);
		$stmt2->execute();
		/// Get the last id inserted to the db which is now this users id for activation and member folder creation ////
		$lastId = $db->lastInsertId();
		$stmt3 = $db->prepare("INSERT INTO activate (user, token) VALUES ('$lastId', :token)");
		$stmt3->bindValue(':token',$token,PDO::PARAM_STR);
		$stmt3->execute();
		//// Send email activation to the new user ////
		$from = "From: Auto Resposder @ Backburnr <admin@backburnr.com>";
		$subject = "IMPORTANT: Activate your Backburnr account";
		$link = 'http://backburnr.com/activate.php?user='.$lastId.'&token='.$token.'';
		//// Start Email Body ////
		$message = "
Thanks for registering an account at backburnr! Were glad you decided to join us in this wacky adventure.
Theres just one last step to set up your account. Please click the link below to confirm your identity and get started.
If the link below is not active please copy and paste it into your browser address bar. See you on the site!

$link
";
		//// Set headers ////
		$headers = 'MIME-Version: 1.0' . "rn";
		$headers .= "Content-type: textrn";
		$headers .= "From: $fromrn";
		/// Send the email now ////
		mail($email1, $subject, $message, $headers, '-f noreply@gotCode.org');
		$db->commit();
		echo "Thanks for joining! Check your email in a few moments to activate your account so that you may log in. See you on the site!";
		exit();
		$db = null;
		exit();
	}
		catch(PDOException $e){
			$db->rollBack();
			echo $e->getMessage();
			$db = null;
			exit();
		}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Register Your Backburnr Account</title>
<link rel="stylesheet" href="style/style.css"/>
<style type="text/css">
.form {
	text-align:center;
	margin-left:auto;
	margin-right:auto;
	width: 48%;
	max-width: 100%;
	border-radius:12px;
	-moz-border-radius:12px;
	padding:6px;
	color:#FFF;
	background-color:#333;
}
.form label {
	margin-left: 10px;
	}
.submit button {
	width: auto;
	padding: 9px 15px;
	background: #4c4c4c; /* Old browsers */
background: -moz-linear-gradient(top,  #4c4c4c 0%, #666666 0%, #595959 30%, #2b2b2b 55%, #000000 100%, #131313 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#4c4c4c), color-stop(0%,#666666), color-stop(30%,#595959), color-stop(55%,#2b2b2b), color-stop(100%,#000000), color-stop(100%,#131313)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  #4c4c4c 0%,#666666 0%,#595959 30%,#2b2b2b 55%,#000000 100%,#131313 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  #4c4c4c 0%,#666666 0%,#595959 30%,#2b2b2b 55%,#000000 100%,#131313 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  #4c4c4c 0%,#666666 0%,#595959 30%,#2b2b2b 55%,#000000 100%,#131313 100%); /* IE10+ */
background: linear-gradient(to bottom,  #4c4c4c 0%,#666666 0%,#595959 30%,#2b2b2b 55%,#000000 100%,#131313 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#4c4c4c', endColorstr='#131313',GradientType=0 ); /* IE6-9 */
	border: #5BDB3C .5px thin solid;
	font-size: 14px;
	color: #FFFFFF;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	cursor:pointer;
	box-shadow:none;
	}
.submit button:hover{
	width: auto;
	padding: 9px 15px;
	border: #5BDB3C .5px thin solid;
	font-size: 14px;
	color: #FFFFFF;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	cursor:pointer;
	box-shadow:none;
	background: #4c4c4c; /* Old browsers */
background: -moz-linear-gradient(top,  #4c4c4c 0%, #131313 0%, #1c1c1c 0%, #2b2b2b 0%, #111111 0%, #474747 0%, #2c2c2c 0%, #000000 0%, #666666 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#4c4c4c), color-stop(0%,#131313), color-stop(0%,#1c1c1c), color-stop(0%,#2b2b2b), color-stop(0%,#111111), color-stop(0%,#474747), color-stop(0%,#2c2c2c), color-stop(0%,#000000), color-stop(100%,#666666)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  #4c4c4c 0%,#131313 0%,#1c1c1c 0%,#2b2b2b 0%,#111111 0%,#474747 0%,#2c2c2c 0%,#000000 0%,#666666 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  #4c4c4c 0%,#131313 0%,#1c1c1c 0%,#2b2b2b 0%,#111111 0%,#474747 0%,#2c2c2c 0%,#000000 0%,#666666 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  #4c4c4c 0%,#131313 0%,#1c1c1c 0%,#2b2b2b 0%,#111111 0%,#474747 0%,#2c2c2c 0%,#000000 0%,#666666 100%); /* IE10+ */
background: linear-gradient(to bottom,  #4c4c4c 0%,#131313 0%,#1c1c1c 0%,#2b2b2b 0%,#111111 0%,#474747 0%,#2c2c2c 0%,#000000 0%,#666666 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#4c4c4c', endColorstr='#666666',GradientType=0 ); /* IE6-9 */
}
.form input{
	padding: 9px;
	border: solid 1px #E5E5E5;
	outline: 0;
	font: normal 13px/100% Verdana, Tahoma, sans-serif;
	width: 86%;
	background:#F8F8F8;
	box-shadow: 3px 3px 5px #CCC inset;
	-moz-box-shadow:3px 3px 5px #CCC inset;
	-webkit-box-shadow:3px 3px 5px #CCC inset;
	border-radius:3px;
	-moz-border-radius:3px;
}
.contentBottom{
	width:68%;
	margin-left:auto;
	margin-right:auto;
}
</style>
<!--[if lte IE 7]>
<style>
.content { margin-right: -1px; } 
ul.nav a { zoom: 1; }
</style>
<![endif]-->
</head>
<body>
<?php include_once("header_template.php"); ?>
<div class="container">
  
  <div class="sidebar1">
   <ul class="nav">
      <li><a href="#">Video Lessons</a></li>
      <li><a href="#">Source Code</a></li>
      <li><a href="#">Contributors</a></li>
      <li><a href="#">FAQ'S</a></li>
    </ul>
    &nbsp;<img src="images/sidebar_logo.png" alt="backburnr" style="width:94%;"/>
    <!-- end .sidebar1 --></div>
  <div class="content">
  <div class="contentBottom">
	<h2 style="text-align:center;">Warning! This site contains vicious dialog and verbal attacs.</h2>
    <p>Users at backburnr.com say whatever they want with no regard for your feelings. We encourage you to do the same.
    If you are a pansy, or you are known for being butt hurt easily, we strongly advise you to go create an account at some other 
    social network where you might be able to find a shoulder to cry on.</p>
    <form action="" method="post" class="form">
    <h3>Sign Up</h3>
<label for="username"><strong>Create a username</strong>
<br />
<input type="text" name="username">
</label>
<br />
<label for="email1"><strong>Email</strong>
<br />
<input type="text" name="email1">
</label>
<br />
<label for="email2"><strong>Confirm Email</strong>
<br />
<input type="text" name="email2">
</label>
<br />
<label for="pass1"><strong>Create Password</strong>
<br />
<input type="password" name="pass1">
</label>
<br />
<label for="pass2"><strong>Confirm Password</strong>
<br />
<input type="password" name="pass2">
</label>
<br />
<br />
<p class="submit">
<button type="submit">Sign Up</button>
</p>
</form>
  <br />
  <br />
  </div>
</div>
    <div class="clearfloat"></div>
  <!-- end .container --></div>
<?php include_once("footer_template.php") ?>
</body>
</html>
