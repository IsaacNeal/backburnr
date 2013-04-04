<?php 
session_start();
if(isset($_SESSION['uid']) && isset($_SESSION['username'])){
	echo "Hello ".$_SESSION['username'];
}else{
	echo '<a href="register.php">Register an account</a><br />
	<p>Already have an account?</p>
	<a href="login.php">Log In</a>';
}