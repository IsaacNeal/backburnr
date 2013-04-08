<?php
$host = "isaacdbBB.db.8909445.hostedresource.com";
$username = "isaacdbBB";
$password = "Bitchass1!";
$db_name = "isaacdbBB";
try{
$db = new PDO('mysql:host='.$host.';dbname='.$db_name,$username,$password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
	echo "Error connecting to database";
}
