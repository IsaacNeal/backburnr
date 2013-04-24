<?php include_once('scripts/check_user.php');
$log_link = "";
if($user_is_logged == true){
	$query = $db->query("SELECT avatar, full_name FROM members WHERE id='$log_user_id'");
	if($query->rowCount() > 0){
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$avatar = $row['avatar'];
			$full_name = $row['full_name'];
		}
		if($full_name != ""){
			$member = $full_name;
		}else{
			$member = $log_uname;
		}
		if($avatar != '' && file_exists("members/$log_user_id/$avatar")){
			$log_link = '<span id="user_top"><a href="#" onclick="return false;" onmousedown="showToggle(\'drop_box\')">'.$member.'&nbsp;&nbsp;<img src="members/'.$log_user_id.'/'.$avatar.'" alt="'.$log_uname.'"/></a></span>';
		}else{
			$log_link = '<span id="user_top"><a href="#" onclick="return false;" onmousedown="showToggle(\'drop_box\')">'.$member.'&nbsp;&nbsp;<img src="images/default_avatar.png" alt="'.$log_uname.'"/></a></span>';
		}
	}
}
else{
	$log_link = '<span class="submit" style="color:#F8F8F8;"><button onclick="window.location=\'login.php\'">Log In</button>
	&nbsp;Or&nbsp;&nbsp;<button onclick="window.location=\'signup_method.php\'">Sign Up</button></span>';
}
?>
<div class="header">
<div style="width:58%; min-width:486px; margin-left:auto; margin-right:auto; text-align:right;">
  <div style="width:46%; float:left;">
   <a href="index.php">
    <img src="images/logo.png" alt="Logo" />
   </a> 
 </div>
 <div style="width:48%; float:left; text-align:right; padding:8px;">
 <?php echo $log_link ?>
 </div>
 </div>
 <br class="clearfloat" />
</div>
<div id="drop_box" style="display:none;width:80%;">
<div style="float:right; width:200px;">
<ul class="nav">
      <li><a href="profile.php?user=<?php echo $log_uname ?>">Profile</a></li>
      <li><a href="logout.php">Log Out</a></li>
    </ul>
    </div>
    <div class="clearfloat"></div>
</div>
<script>
function showToggle(e){
	var target = document.getElementById(e);
	if(target.style.display == 'none'){
		target.style.display = 'block';
	}else{
		target.style.display = 'none';
	}
}
</script>