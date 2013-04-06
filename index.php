<?php 
//session_start();
include_once("scripts/check_user.php");
if($user_is_logged == true){
	echo "Hello ".$_SESSION['username'].' <a href="logout.php">Log out</a>';
}else{
	echo $log_uname." ".$log_user_id.' <a href="register.php">Register an account</a><br />
	<p>Already have an account?</p>
	<a href="login.php">Log In</a>';
}