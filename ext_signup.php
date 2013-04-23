<?php
//include_once("scripts/connect.php");
include_once("scripts/check_user.php");
if($user_is_logged == true){
	header("location: index.php");
	exit();
}
if(isset($_POST['displayName'])){
    $full_name = strip_tags($_POST['displayName']);
    $email = strip_tags($_POST['email']);
    $email_verified = strip_tags($_POST['email_verified']);
    $ext_id = strip_tags($_POST['exi_id']);
    // make sure no fields are blank /////
    /* if(trim($full_name) == "" || trim($email) == "" || trim($ext_id) == ""){
        echo "Error gathering your Facebook data: All fields are required. Please refresh your browser and try again.";
        $db = null;
        exit();
    } */
    /// Make sure both email fields match /////
    if($email_verified == 'false'){
        echo "Your email could not be verified, Please try later.";
		$db = null;
        exit();
    }
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		echo "The email provided is invalid. Press try using another.";
		$db = null;
        exit();
	}
	//// query to check if email is in the db already ////
	$stmt = $db->prepare("SELECT email FROM members WHERE email=:email LIMIT 1");
	$stmt->bindValue(':email',$email,PDO::PARAM_STR);
	try{
	$stmt->execute();
	$count = $stmt->rowCount();
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
	try{
		$db->beginTransaction();
		$ipaddress = getenv('REMOTE_ADDR');
		$stmt2 = $db->prepare("INSERT INTO members (ext_id, full_name, email, signup_date, ipaddress) 
		VALUES (:ext_id, :full_name, :email, now(), :ipaddress)");
		$stmt2->bindParam(':ext_id', $ext_id, PDO::PARAM_INT);
		$stmt2->bindParam(':full_name', $full_name, PDO::PARAM_STR);
		$stmt2->bindParam(':email',$email,PDO::PARAM_STR);
		$stmt2->bindParam(':ipaddress',$ipaddress,PDO::PARAM_INT);
		$stmt2->execute();
		/// Get the last id inserted to the db which is now this users id for activation and member folder creation ////
		$lastId = $db->lastInsertId();
		$token = rand(999999999,9999999999999999);
		$stmt3 = $db->prepare("INSERT INTO activate (user, token) VALUES ('$lastId', :token)");
		$stmt3->bindValue(':token',$token,PDO::PARAM_STR);
		$stmt3->execute();
		//// Send email activation to the new user ////
		$from = "From: Auto Resposder @ Backburnr <admin@your-email.com>";
		$subject = "IMPORTANT: Activate your Backburnr account";
		$link = 'http://backburnr.com/last_step.php?user='.$lastId.'&token='.$token.'';
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
		mail($email, $subject, $message, $headers, '-f noreply@backburnr.com');
		//mail($email1, $subject, $message, $headers, '-f noreply@your-email.com');
		$db->commit();
		echo "Thanks for joining! Check your email in a few moments to activate your account so that you may log in. See you on the site!";
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
