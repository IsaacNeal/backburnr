<?php
include_once("scripts/connect.php");
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
    $hmac = hash_hmac('sha512', $pass1, file_get_contents('http://www.worldofwebcraft.com/random/key.txt'));
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
<title>Register Your Backburnr.com account</title>
</head>

<body>
<form action="" method="post">
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
<input type="submit" value="Register">
</form>
</body>
</html>