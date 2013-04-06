<?php
include_once("scripts/check_user.php"); 
if($user_is_logged == true){
	header("location: index.php");
	exit();
}
if(isset($_POST['email']) && trim($_POST['email']) != ""){
	$email = strip_tags($_POST['email']);
	$password = $_POST['password'];
	$hmac = hash_hmac('sha512', $password, file_get_contents('path/to/key.txt'));
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
.form {
	text-align:center;
	margin-left:auto;
	margin-right:auto;
	width: 48%;
	max-width: 100%;
	border-radius:12px;
	-moz-border-radius:12px;
	padding:6px;
	color:#FFF;
	background-color:#333;
}
.form label {
	margin-left: 10px;
	}
.submit button {
	width: auto;
	padding: 9px 15px;
	background: #4c4c4c; /* Old browsers */
background: -moz-linear-gradient(top,  #4c4c4c 0%, #666666 0%, #595959 30%, #2b2b2b 55%, #000000 100%, #131313 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#4c4c4c), color-stop(0%,#666666), color-stop(30%,#595959), color-stop(55%,#2b2b2b), color-stop(100%,#000000), color-stop(100%,#131313)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  #4c4c4c 0%,#666666 0%,#595959 30%,#2b2b2b 55%,#000000 100%,#131313 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  #4c4c4c 0%,#666666 0%,#595959 30%,#2b2b2b 55%,#000000 100%,#131313 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  #4c4c4c 0%,#666666 0%,#595959 30%,#2b2b2b 55%,#000000 100%,#131313 100%); /* IE10+ */
background: linear-gradient(to bottom,  #4c4c4c 0%,#666666 0%,#595959 30%,#2b2b2b 55%,#000000 100%,#131313 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#4c4c4c', endColorstr='#131313',GradientType=0 ); /* IE6-9 */
	border: #5BDB3C .5px thin solid;
	font-size: 14px;
	color: #FFFFFF;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	cursor:pointer;
	box-shadow:none;
	}
.submit button:hover{
	width: auto;
	padding: 9px 15px;
	border: #5BDB3C .5px thin solid;
	font-size: 14px;
	color: #FFFFFF;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	cursor:pointer;
	box-shadow:none;
	background: #4c4c4c; /* Old browsers */
background: -moz-linear-gradient(top,  #4c4c4c 0%, #131313 0%, #1c1c1c 0%, #2b2b2b 0%, #111111 0%, #474747 0%, #2c2c2c 0%, #000000 0%, #666666 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#4c4c4c), color-stop(0%,#131313), color-stop(0%,#1c1c1c), color-stop(0%,#2b2b2b), color-stop(0%,#111111), color-stop(0%,#474747), color-stop(0%,#2c2c2c), color-stop(0%,#000000), color-stop(100%,#666666)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  #4c4c4c 0%,#131313 0%,#1c1c1c 0%,#2b2b2b 0%,#111111 0%,#474747 0%,#2c2c2c 0%,#000000 0%,#666666 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  #4c4c4c 0%,#131313 0%,#1c1c1c 0%,#2b2b2b 0%,#111111 0%,#474747 0%,#2c2c2c 0%,#000000 0%,#666666 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  #4c4c4c 0%,#131313 0%,#1c1c1c 0%,#2b2b2b 0%,#111111 0%,#474747 0%,#2c2c2c 0%,#000000 0%,#666666 100%); /* IE10+ */
background: linear-gradient(to bottom,  #4c4c4c 0%,#131313 0%,#1c1c1c 0%,#2b2b2b 0%,#111111 0%,#474747 0%,#2c2c2c 0%,#000000 0%,#666666 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#4c4c4c', endColorstr='#666666',GradientType=0 ); /* IE6-9 */
}
.form input{
	padding: 9px;
	border: solid 1px #E5E5E5;
	outline: 0;
	font: normal 13px/100% Verdana, Tahoma, sans-serif;
	width: 86%;
	background:#F8F8F8;
	box-shadow: 3px 3px 5px #CCC inset;
	-moz-box-shadow:3px 3px 5px #CCC inset;
	-webkit-box-shadow:3px 3px 5px #CCC inset;
	border-radius:3px;
	-moz-border-radius:3px;
}
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
  
  <div class="sidebar1">
   <ul class="nav">
      <li><a href="#">Video Lessons</a></li>
      <li><a href="#">Source Code</a></li>
      <li><a href="#">Contributors</a></li>
      <li><a href="#">FAQ'S</a></li>
    </ul>
    &nbsp;<img src="images/sidebar_logo.png" alt="backburnr" style="width:94%;"/>
    <!-- end .sidebar1 --></div>
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
