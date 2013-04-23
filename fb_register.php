<?php
$success_msg =  '';
include_once("scripts/check_user.php");
if($user_is_logged == true){
	header("location: index.php");
	exit();
}
function ArrayBinder(&$pdoStatement, &$array){
	foreach($array as $k=>$v){
		$pdoStatement->bindValue(':'.$k,$v);
	}
}
	$msg = "";
	$app_id = "602371646439600";
	$app_secret = "a0ddca2b422dd1b58277740ce0ccafda";
	$my_url = "http://www.backburnr.com/fb_register.php";
	///////////////////////////////////////////////
	$code = $_REQUEST["code"];

   if(empty($code)) {
     $_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection
     $dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
       . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
       . $_SESSION['state'] . "&scope=email,user_about_me&fields=id";

     echo("<script> top.location.href='" . $dialog_url . "'</script>");
   }
    if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {
     $token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
       . "&client_secret=" . $app_secret . "&code=" . $code;

     $response = file_get_contents($token_url);
     $params = null;
     parse_str($response, $params);

     $_SESSION['access_token'] = $params['access_token'];

     $graph_url = "https://graph.facebook.com/me?access_token=" 
       . $params['access_token'];

     $user = json_decode(file_get_contents($graph_url));
	 $picture="https://graph.facebook.com/me/picture?access_token=".$_SESSION['access_token']."&type=normal";
	 $ext_id = $user->id;
	 $name = $user->name;
	 $email = $user->email;
	 if(empty($ext_id) || empty($name) || empty($email)){
		 echo "Sorry, there was an error proceccing your Facebook data. Please try signing up in again later";
		 exit();
	 }
	 $ip = getenv('REMOTE_ADDR');
     $ip = preg_replace('#[^0-9.]#i', '', $ip);
   	/////////////////////////////////////////////////
	$has_password = false;
	$pass_stmt = $db->prepare("SELECT password from members WHERE ext_id=:ext_id LIMIT 1");
	$pass_stmt->bindValue(':ext_id',$ext_id,PDO::PARAM_INT);
	try{
		$pass_stmt->execute();
	}
	catch(PDOException $e){
		echo $e->getMessage();
		$db = null;
		exit();
	}
	 if($pass_stmt->rowCount() > 0){
		 echo "That Facebook user is already in our system. Someone has either stolen your Facebook password and email, or you have already linked this account to a profile you own here.";
		 $db = null;
		 exit();
	 }
	$stmt = $db->prepare("SELECT email FROM members WHERE email=:email LIMIT 1");
	$stmt->bindValue(':email',$email,PDO::PARAM_STR);
	try{
	$stmt->execute();
	$count = $stmt->rowCount();
	}
	catch(PDOException $e){
		echo $e->getMessage();
			$db = null;
			exit();
	}
	if($count > 0){
		echo "Sorry, that email is already in use in the system";
		$db = null;
		exit();
	}
	$userForm = '<div id="form" class="form">
	<label for="displayName">
	<form id="signup_form"><strong>How your name will appear</strong>
<br />
<input type="text" id="displayName" name="displayName" value="'.$user->name.'">
</label>
<br />
<label for="email"><strong>Your email</strong>
<br />
<input type="text" id="email" name="email" value="'.$user->email.'">
</label>
<input type="hidden" id="ext_id" name="ext_id" value="'.$user->id.'">
<br />
<br />
<p class="submit">
<button id="signUpBtn" type="submit" onclick="return false;">Next</button>
</form></div>';
}else{
	echo 'Error retrieving your Facebook data. Please try later, or try signing up normally without your Facebook account.';
	$db = null;
	exit();
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Register Your Backburnr Account</title>
<link rel="stylesheet" href="style/style.css"/>
<script type="text/javascript" src="js/serialize.js"></script>
<script type="text/javascript" src="js/ajax.js"></script>
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
	<h2 style="text-align:center;">Warning! This site contains vicious dialog and verbal attacs.</h2>
    <p id="message_span">To register your Backnurnr.com account using your Facebook profile information click the "Next" button below. The data we have gathered from Facebook, your email address, public id, and basic details like first and last name are used soley to identify you here on the site.</p>
  <br />
  <br />
  <?php echo $userForm ?>
  </div>
</div>
    <div class="clearfloat"></div>
  <!-- end .container --></div>
<?php include_once("footer_template.php") ?>
<script>
document.getElementById('signUpBtn').onmousedown = function(){
ajax('signup_form',
	 'POST', 
	 'ext_signup.php', 
	 'form'
)};
</script>
</body>
</html>
