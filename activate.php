<?php
if(isset($_GET['user']) && $_GET['user'] != "" && isset($_GET['token']) && $_GET['token'] != ""){
	include_once("scripts/connect.php");
	$user = preg_replace('#[^0-9]#', '', $_GET['user']);
	$token = preg_replace('#[^a-z0-9]#i', '', $_GET['token']);
	$stmt = $db->prepare("SELECT user, token FROM activate WHERE user=:user AND token=:token LIMIT 1");
	$stmt->bindValue(':user',$user,PDO::PARAM_INT);
	$stmt->bindValue(':token',$token,PDO::PARAM_STR);
	try{
		$stmt->execute();
		$count = $stmt->rowCount();
		if($count > 0){
			try{
				$db->beginTransaction();
				$updateSQL = $db->prepare("UPDATE members SET activated='1' WHERE id=:user LIMIT 1");
				$updateSQL->bindValue(':user',$user,PDO::PARAM_INT);
				$updateSQL->execute();
				$deleteSQL = $db->prepare("DELETE FROM activate WHERE user=:user AND token=:token LIMIT 1");
				$deleteSQL->bindValue(':user',$user,PDO::PARAM_INT);
				$deleteSQL->bindValue(':token',$token,PDO::PARAM_STR);
				$deleteSQL->execute();
				if(!file_exists("members/$user")){
					mkdir("members/$user", 0755);
				}
				$db->commit();
				echo 'Your account has been activated! Click the link to log in: <a href="login.php">Log In</a>';
				$db = null;
				exit();
			
			}
			catch(PDOException $e){
				$db->rollBack();
				echo $e->getMessage();
			}
		}else{
			echo "Sorry, There has been an error. Maybe try registering again derp.";
			$db = null;
			exit();
		}
	}
	catch(PDOException $e){
		echo $e->getMessage();
		$db = null;
		exit();
	}
}
?>