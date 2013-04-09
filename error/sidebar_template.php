<?php 
$logLink = "";
$signUp_link = "";
$profile_link = "";
if($user_is_logged == true){
	$profile_link = '<li><a href="#">'.$log_uname.'</a></li>';
	$logLink = '<li><a href="../logout.php">Log Out</a></li>';
}else{
	$logLink = '<li><a href="../login.php">Log In</a></li>';
	$signUp_link = '<li><a href="register.php">Sign Up</a></li>';
}
?>
<div class="sidebar1">
   <ul class="nav">
   	  <?php echo $profile_link; ?>
      <?php echo $logLink; ?>
      <?php echo $signUp_link; ?>
      <li><a href="#">Video Lessons</a></li>
      <li><a href="#">Source Code</a></li>
      <li><a href="#">Contributors</a></li>
      <li><a href="#">FAQ'S</a></li>
    </ul>
    <div style="padding:6px; text-align:center;"><strong>You should not go places you are not wanted.</strong></div>
    <div style="padding:6px;">
    <img src="../images/sidebar_logo.png" alt="backburnr" style="width:94%;"/>
  </div>
    
</div>