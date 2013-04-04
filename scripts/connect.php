<?php
$db_host = "localhost";
// Place the username for the MySQL database here
$db_username = "isaac"; 
// Place the password for the MySQL database here
$db_pass = "password"; 
// Place the name for the MySQL database here
$db_name = "mytesting_db";
try{
$db = new PDO('mysql:host='.$db_host.';dbname='.$db_name,$db_username,$db_pass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch(PDOException $e){
	echo $e->getMessage();
	exit();
}