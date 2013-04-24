<?php 
function ArrayBinder(&$pdoStatement, &$array){
	foreach($array as $k=>$v){
		$pdoStatement->bindValue(':'.$k,$v);
	}
}
	$msg = "";
	$app_id = "113879582104655";
	$app_secret = "d3bfe5e56d06049f38af1ddd07bb7cce";
	$my_url = "http://www.worldofwebcraft.com/secure_fb_login.php";
	session_start();
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
	 $plusid = $user->id;
	 $name = $user->name;
	 $email = $user->email;
	 if(empty($plusid) || empty($name) || empty($email)){
		 echo "Sorry, there was an error proceccing your Facebook data. Please try logging in again later";
		 exit();
	 }
	 $ip = getenv('REMOTE_ADDR');
     $ip = preg_replace('#[^0-9.]#i', '', $ip);
	 $plusid = preg_replace('#[^0-9]#', '', $plusid);
	 $name = preg_replace('#[^0-9 a-z]#i', '', $name);
   	/////////////////////////////////////////////////
	$bad = array("'","`",'"');
	$good = array("","",'');
	$email = str_replace($bad, $good, $email);
	$photo = str_replace($bad, $good, $photo);
	 //////////////////////////////////////////
	 $password = rand(999999999,9999999999999999);
	 include_once 'php_inc_files/db_connect_pdo.php';
	 $stmt = $db->prepare("SELECT pass FROM users WHERE plusid=:plusid LIMIT 1");
	$stmt->bindValue('plusid',$plusid,PDO::PARAM_INT);
	try{
		$stmt->execute();
		$count = $stmt->rowCount();
	}
	catch(PDOException $e){
		echo "Sorry, There was a server error. Please try logging in again later.";
		$db = null;
		exit();
	}
	if($count < 1){
		try{
			$db->beginTransaction();
			$regiStmt = $db->prepare("INSERT INTO users (plusid, email, emailverified, pass, name, picture, locale, gender, firstlogin, lastlogin, lastchecknotes, ip, exp) VALUES(:plusid,:email,:emailverified,:password,:name,:picture,:locale,:gender,now(),now(),now(),:ip,'5')");
		$arr = array(
			"plusid"=>$plusid,
			"email"=>$email,
			"emailverified"=>$emailverified,
			"password"=>$password,
			"name"=>$name,
			"picture"=>$picture,
			"locale"=>$locale,
			"gender"=>$gender,
			"ip"=>$ip
		);
		ArrayBinder($regiStmt,$arr);
		$uOpsStmt = $db->prepare("INSERT INTO useroptions (user) VALUES (:plusid)");
		$uOpsStmt->bindValue(':plusid',$plusid,PDO::PARAM_INT);
		$regiStmt->execute();
		$uOpsStmt->execute();
		$db->commit();
		}
		catch(PDOException $e){
			$db->rollBack();
			echo "Sorry, There was a server error. Please try logging in again later.";
			$db = null;
			exit();
		}
	}else{
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$password = $row['pass'];
		}
		$updateStmt = $db->prepare("UPDATE users SET email=:email, name=:name, picture=:picture, locale=:locale, gender=:gender lastlogin=now() WHERE plusid=:plusid LIMIT 1");
		$array = array(
			"email"=>$email,
			"name"=>$name,
			"picture"=>$picture,
			"locale"=>$locale,
			"gender"=>$gender,
			"plusid"=>$plusid
		);
		ArrayBinder($updateStmt,$array);
		try{
			$updateStmt->execute();
		}
		catch(PDOException $e){
			echo "Sorry, There was a server error. Please try logging in again later.";
			$db = null;
			exit();
		}
	}
	// Set the session variables
	$_SESSION['plusid'] = $plusid;
	$_SESSION['name'] = $name;
	$_SESSION['pass'] = $password;
	unset($_SESSION['access_token']);
	// Set cookie files for site remembrance	
	setcookie("ci", base64_encode($plusid), time()+60*60*24*100, "/", "", "", TRUE); // Cookie expire 30 days
    setcookie("cn", base64_encode($name), time()+60*60*24*100, "/", "", "", TRUE); 
	setcookie("cp", base64_encode($password), time()+60*60*24*100, "/", "", "", TRUE);
	$db = null;
	echo '<div style=" line-height:1.5em; text-align:center;">';
	echo '<img src="wow_images/wowlogo3D.jpg" width="263" height="180" alt="World of Webcraft" style="display:block; margin:10px auto;">';
	echo '<h3>You have successfully logged in using your Facebook account</h3>';
	echo '<p><a href="index.php">Click here to enter the site</a></p>';
	echo '</div>';
    exit();
}else{
	echo "Error retrieving you FaceBook data";
	$db = null;
	exit();
}
?>