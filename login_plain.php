<?php
session_start();
include_once("scripts/connect.php"); 
if(isset($_POST['email']) && trim($_POST['email']) != ""){
	$email = strip_tags($_POST['email']);
	$password = $_POST['password'];
	$hmac = hash_hmac('sha512', $password, file_get_contents('http://www.worldofwebcraft.com/random/key.txt'));
	$stmt1 = $db->prepare("SELECT id, username, password FROM members WHERE email=:email AND activated='1' LIMIT 1");
	$stmt1->bindValue(':email',$email,PDO::PARAM_INT);
	try{
		$stmt1->execute();
		$count = $stmt1->rowCount();
		if($count > 0){
			while($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
				$uid = $row['id'];
				$username = $row['username'];
				$hash = $row['password'];
			}
			if (crypt($hmac, $hash) === $hash) {
				$db->query("UPDATE members SET lastlog=now() WHERE id='$uid' LIMIT 1");
				$_SESSION['uid'] = $uid;
				$_SESSION['username'] = $username;
				$_SESSION['password'] = $hash;
				setcookie("id", $uid, strtotime( '+30 days' ), "/", "", "", TRUE);
				setcookie("username", $username, strtotime( '+30 days' ), "/", "", "", TRUE);
    			setcookie("password", $hash, strtotime( '+30 days' ), "/", "", "", TRUE); 
				/* echo 'Valid password<br />'.$_SESSION['uid'].'<br />'.$_SESSION['username'].'<br />'.$_SESSION['password'].'
				<br />'.$_COOKIE['id']; */
				header("location: index.php");
				exit();
			} else {
				echo 'Invalid password Press back and try again<br />';
				exit();
			}
		}
		else{
			echo "A user with that email address does not exist here";
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
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Log In</title>
</head>

<body>
<strong>Email</strong>
<br />
<form action="" method="post">
<input type="text" name="email">
<br />
<strong>Password</strong><br />
<input type="password" name="password">
<br />
<input type="submit" value="Log In">
</form>
</body>
</html>