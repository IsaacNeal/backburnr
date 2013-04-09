<?php
include_once("scripts/check_user.php"); 
if($user_is_logged == true){
	header("location: index.php");
	exit();
}
if(isset($_POST['email']) && trim($_POST['email']) != ""){
	$email = strip_tags($_POST['email']);
	$password = $_POST['password'];
	$hmac = hash_hmac('sha512', $password, file_get_contents('secret/key.txt'));
	$stmt1 = $db->prepare("SELECT id, username, password FROM members WHERE email=:email AND activated='1' LIMIT 1");
	$stmt1->bindValue(':email',$email,PDO::PARAM_STR);
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
<title>Page Template</title>
<link rel="stylesheet" href="style/style.css"/>
<style type="text/css">
.contentBottom{
	width:68%;
	margin-left:auto;
	margin-right:auto;
}
</style>
<!--[if lte IE 7]>
<style>
.content { margin-right: -1px; } 
ul.nav a { zoom: 1; }
</style>
<![endif]-->
</head>
<body>
<?php include_once("header_template.php"); ?>
<div class="container">
  <?php include_once("sidebar_template.php") ?>
  <div class="content">
  <div class="contentBottom">
	<h2 style="text-align:center;">Warning! This site contains vicious dialog.</h2>
    <p>Users at backburnr.com say whatever they want with no regard for your feelings. We encourage you to do the same.
    If you are a pansy, or you are known for being butt hurt easily, we strongly advise you to go log in to some other 
    social network where you might be able to find a shoulder to cry on.</p>
    <form action="" method="post" class="form">
    <h3>Log In</h3>
    <strong>Email</strong><br />
	<input type="text" name="email">
	<br />
<strong>Password</strong><br />
	<input type="password" name="password">
	<br />
    <br />
    <p class="submit">
	<button type="submit">Log In</button>
    </p>
  </form>
  <br />
  <br />
  </div>
</div>
    <div class="clearfloat"></div>
  <!-- end .container --></div>
<?php include_once("footer_template.php") ?>
</body>
</html>
