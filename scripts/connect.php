<?php
// Usualy "localhost" but could be different on different servers
$db_host = "localhost";
// Place the username for the MySQL database here
$db_username = "USERNAME"; 
// Place the password for the MySQL database here
$db_pass = "PASSWORD"; 
// Place the name for the MySQL database here
$db_name = "DB_NAME";
try{
	$db = new PDO('mysql:host='.$db_host.';dbname='.$db_name,$db_username,$db_pass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
	echo $e->getMessage();
	exit();
}