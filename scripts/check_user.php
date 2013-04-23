<?php
session_start();
include_once("connect.php");
$user_is_logged = false;
$log_user_id = "";
$log_uname = "";
$log_pass = "";
if(isset($_SESSION['uid']) && isset($_SESSION['username']) && isset($_SESSION['password'])){
	$log_user_id = preg_replace('#[^0-9]#', '', $_SESSION['uid']);
	$log_uname = preg_replace('#[^a-z0-9]#i', '', $_SESSION['username']);
	$log_pass = preg_replace('#[^a-z0-9]#i', '', $_SESSION['password']);
	$stmt = $db->prepare("SELECT id FROM members WHERE id=:log_user_id LIMIT 1");
	$stmt->bindValue(':log_user_id',$log_user_id,PDO::PARAM_INT);
	try{
		$stmt->execute();
		$count = $stmt->rowCount();
		 if($count > 0){
			 $user_is_logged = true;
		 }
	}
	catch(PDOException $e){
		return false;
	}
}else if(isset($_COOKIE['id']) && isset($_COOKIE['username']) && isset($_COOKIE['password'])){
	$_SESSION['uid'] = preg_replace('#[^0-9]#', '', $_COOKIE['id']);
	$_SESSION['username'] = preg_replace('#[^a-z0-9]#i', '', $_COOKIE['username']);
	$_SESSION['password'] = preg_replace('#[^a-z0-9]#i', '', $_COOKIE['password']);
	$log_user_id = $_SESSION['id'];
	$log_uname = $_SESSION['username'];
	$log_pass = $_SESSION['password'];
	$stmt = $db->prepare("SELECT id FROM members WHERE id=:log_user_id LIMIT 1");
	$stmt->bindValue(':log_user_id',$log_user_id,PDO::PARAM_INT);
	try{
		$stmt->execute();
		$count = $stmt->rowCount();
		 if($count > 0){
			 $user_is_logged = true;
		 }
	}
	catch(PDOException $e){
		return false;
	}
	if($user_is_logged == true){
		$db->query("UPDATE members SET lastlog=now() WHERE id='$log_user_id' LIMIT 1");
	}
}