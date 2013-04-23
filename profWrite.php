<?php
include_once("scripts/check_user.php");
function ArrayBinder(&$pdoStatement, &$array){
	foreach($array as $k=>$v){
		$pdoStatement->bindValue(':'.$k,$v);
	}
}
if(isset($_POST['fullname'])){
	$fullname = $_POST['fullname'];
	$country = $_POST['country'];
	$state = $_POST['state'];
	$city = $_POST['city'];
	$birthmonth = preg_replace('#[^0-9]#i', '', $_POST['birthmonth']);
	$birthday = preg_replace('#[^0-9]#i', '', $_POST['birthday']);
	$birthyear = preg_replace('#[^0-9]#i', '', $_POST['birthyear']);
	if(strlen($birthmonth) < 2 || strlen($birthday < 2)){
		echo 'ERROR: Your birth month and birth day fields must contain at least 2 characters: mm/dd';
		$db = null;
		exit();
	}
	$full_birthday = "$birthyear-$birthmonth-$birthday";
	$gender = $_POST['gender'];
	$stmt = $db->prepare("UPDATE members SET full_name=:fullname, country=:country, state=:state, city=:city, 
	gender=:gender, birthday=:full_birthday WHERE id=:log_user_id AND username=:log_uname LIMIT 1");
	$arr = array(
		"fullname"		=>	$fullname,
		"country"		=>	$country,
		"state"			=>	$state,
		"city"			=>	$city,
		"gender"		=>	$gender,
		"full_birthday"	=>	$full_birthday,
		"log_user_id"	=>	$log_user_id,
		"log_uname"		=>	$log_uname
	);
	ArrayBinder($stmt,$arr);
	try{
		$stmt->execute();
		echo "Your info has been updated";
		$db = null;
		exit();
	}
	catch(PDOException $e){
		echo $e->getMessage();
		$db = null;
		exit();
	}
}
//// Avatar Upload Section /////////////
if(isset($_FILES['avatar']['name']) && $_FILES['avatar']['tmp_name'] != ''){
	if(!$_FILES['avatar']['name']){
		header("location: profile.php?user=$log_uname&msg=Please select an image");
	}
	require("classes/upload.php");
	$files_array = array();
	$files_array['fileName'] = $_FILES['avatar']['name'];
	$files_array['fileType'] = $_FILES['avatar']['type'];
	$files_array['fileSize'] = $_FILES['avatar']['size'];
	$files_array['file_tmp_name'] = $_FILES['avatar']['tmp_name'];
	$files_array['fileErrors'] = $_FILES['avatar']['error'];
	$upload = new Upload($files_array,'1048576',$log_user_id);
	//$fileArray = $upload->getFileArray();
	$regex = "/^.*\.(jpg|jpeg|png|gif)$/i";
	if(count($upload->checkFile($regex)) == 0){
		$moveit = $upload->moveFile("","/members");
		if($moveit == true){
			$query = $db->query("UPDATE members SET avatar='$moveit' WHERE username='$log_uname' AND id='$log_user_id' LIMIT 1");
			require("classes/img_resize.php");
				$img = new ResizeIMG($log_user_id);
				$img->loadFile("/members","$moveit");
				$img->resizeH(200);
				$img->saveFile("/members","$moveit",90);
				header("location: profile.php?user=$log_uname");
				$db = null;
				exit();
			//echo $moveit;
		}else{
			$errormsg = $upload->checkFile($regex);
			echo $errormsg[0];
		}
	}else{
		$errormsg = $upload->checkFile($regex);
		echo $errormsg[0];
	}
	
}else{
	header("location: profile.php?user=$log_uname");
}
/////// Banner Upload Section ////////////
if(isset($_FILES['banner']['name']) && $_FILES['banner']['tmp_name'] != ''){
	if($_FILES['banner']['name'] == ""){
		header("location: profile.php?user=$log_uname&msg=Please select an image");
	}
	require("classes/upload.php");
	if(!file_exists("banners/$log_user_id")){
		mkdir("banners/$log_user_id", 0755);
	}
	$files_array = array();
	$files_array['fileName'] = $_FILES['banner']['name'];
	$files_array['fileType'] = $_FILES['banner']['type'];
	$files_array['fileSize'] = $_FILES['banner']['size'];
	$files_array['file_tmp_name'] = $_FILES['banner']['tmp_name'];
	$files_array['fileErrors'] = $_FILES['banner']['error'];
	$upload = new Upload($files_array,'1572864',$log_user_id);
	//$fileArray = $upload->getFileArray();
	$regex = "/^.*\.(jpg|jpeg|png|gif)$/i";
	if(count($upload->checkFile($regex)) == 0){
		$moveit = $upload->moveFile("","/banners");
		if($moveit == true){
			$query = $db->query("UPDATE members SET banner='$moveit' WHERE username='$log_uname' AND id='$log_user_id' LIMIT 1");
			require("classes/img_resize.php");
				$img = new ResizeIMG($log_user_id);
				$img->loadFile("/banners","$moveit");
				$img->resizew(800);
				$img->saveFile("/banners","$moveit",90);
				header("location: profile.php?user=$log_uname");
				$db = null;
				exit();
			//echo $moveit;
		}else{
			$errormsg = $upload->checkFile($regex);
			echo $errormsg[0];
		}
	}else{
		$errormsg = $upload->checkFile($regex);
		echo $errormsg[0];
	}
	
}else{
	header("location: profile.php?user=$log_uname");
}