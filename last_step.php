<?php
require("scripts/check_user.php");
if($user_is_logged == true){
	header("location: index.php");
	$db = null;
	exit();
}
if(!isset($_GET['user']) || $_GET['user'] == "" || !isset($_GET['token']) || $_GET['token'] == ""){
	header("location: index.php");
	$db = null;
	exit();
}
	$user = preg_replace('#[^0-9]#', '', $_GET['user']);
	$token = preg_replace('#[^0-9]#i', '', $_GET['token']);
	$stmt = $db->prepare("SELECT user, token FROM activate WHERE user=:user AND token=:token LIMIT 1");
	$stmt->bindValue(':user',$user,PDO::PARAM_INT);
	$stmt->bindValue(':token',$token,PDO::PARAM_STR);
	try{
		$stmt->execute();
		$count = $stmt->rowCount();
	}
	catch(PDOException $e){
		echo $e->getMessage();
		$db = null;
		exit();
	}
	if($stmt->rowCount() == 0){
		echo "A user with that email and or access token does not exist in our system";
		$db = null;
		exit();
}
if(isset($_POST['username']) && trim($_POST['username']) != ""){
	$username = preg_replace('#[^a-z0-9]#i', '', $_POST['username']);
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    // make sure no fields are blank /////
    if(trim($username) == "" || trim($pass1) == "" || trim($pass2) == ""){
        echo "Error: All fields are required. Please press back in your browser and try again.";
        $db = null;
        exit();
    }
	if($pass1 != $pass2){
        echo "Your password fields do not match. Press back and try again";
		$db = null;
        exit();
    }
	//// create the hmac /////
    $hmac = hash_hmac('sha512', $pass1, file_get_contents('secret/key.txt'));
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
	$stmt = $db->prepare("SELECT username FROM members WHERE username=:username LIMIT 1");
	$stmt->bindValue(':username',$username,PDO::PARAM_STR);
	try{
	$stmt->execute();
	$count = $stmt->rowCount();
	}
	catch(PDOException $e){
		echo $e->getMessage();
			$db = null;
			exit();
	}
	if($count > 0){
		echo "Sorry, that username is already in use in the system. Please press back in your browser and try again.";
		$db = null;
		exit();
	}
	try{
		$db->beginTransaction();
		$stmt2 = $db->prepare("UPDATE members SET username=:username, activated='1', password=:bcrypt WHERE id=:user");
		$stmt2->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt2->bindParam(':bcrypt',$bcrypt,PDO::PARAM_STR);
		$stmt2->bindParam(':user',$user,PDO::PARAM_INT);
		$stmt2->execute();
		$stmt3 = $db->prepare("DELETE FROM activate WHERE user=:user AND token=:token LIMIT 1");
		$stmt3->bindParam(':user',$user,PDO::PARAM_INT);
		$stmt3->bindParam(':token',$token,PDO::PARAM_INT);
		$stmt3->execute();
		$stmt4 = $db->prepare("UPDATE members SET lastlog=now() WHERE id=:user LIMIT 1");
		$stmt4->bindParam(':user',$user,PDO::PARAM_INT);
		$stmt4->execute();
		$db->commit();
		if(!file_exists("members/$user")){
			mkdir("members/$user", 0755);
		}
			$_SESSION['uid'] = $user;
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $bcrypt;
			setcookie("id", $user, strtotime( '+30 days' ), "/", "", "", TRUE);
			setcookie("username", $username, strtotime( '+30 days' ), "/", "", "", TRUE);
    		setcookie("password", $bcrypt, strtotime( '+30 days' ), "/", "", "", TRUE);
			header("location: profile.php?user=$username");
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
  <?php include_once("sidebar_template.php") ?>
  <div class="content">
  <div class="contentBottom">
	<h2 style="text-align:center;">Last step for registration, and last chance to turn back now!</h2>
    <p>This is your last chance to turn back and try to forget you were ever unfortunate enough to find this
    horrible, god forsaken corner of the internet. However, if you are crazy enough to proceed please create a 
    username and password for the site. For your protection, the password 
    you create for Backburnr should be different than your password on FaceBook. By finishing this step of the 
    process you are agreeing to all Backburnr.com terms of use. <a href="#">View Terms</a>
    </p>
    <form action="" method="post" class="form">
    <h3 style="text-align:center">Sign Up</h3>
<label for="username"><strong>Username</strong>
<br />
<input type="text" name="username">
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
<button type="submit">Finish Registration</button>
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