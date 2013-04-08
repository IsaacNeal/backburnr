<?php 
$logLink = "";
$signUp_link = "";
include_once("scripts/check_user.php");
if($user_is_logged == true){
	$logLink = '<li><a href="logout.php">Log Out</a></li>';
}else{
	$logLink = '<li><a href="login.php">Log In</a></li>';
	$signUp_link = '<li><a href="register.php">Sign Up</a></li>';
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Page Template</title>
<link rel="stylesheet" href="style/style.css"/>
<style type="text/css">
.banner{
	margin: 0px auto;
	width:78%;
	text-align:center;
	border:#333 1px thin solid;
	box-shadow: 1px 1px 6px #333;
	background-color:#333;
	color:#333;
	text-shadow: 0px -1px #000000;
	border-radius:3px;
	-moz-border-radius:3px;
}
.topContent{
	width:44%;
	min-height:480px;
	float: left;
	text-align:center;
	margin-left:24px;
	box-shadow: 1px 1px 6px #333;
	color:333;
	word-break:break-strict;
}
.contentTop p a:link{
	color: #5BDB3C;
	text-decoration: none;
}
.contentTop p a:hover, a:active, a:focus{
	color: #5BDB3C;
	text-decoration: underline;
}
.topContent p{
	color:333;
	text-align:left;
}
.topContent li{
	text-align:left;
	color:333;
}
.topContent img{
	max-width:80%;
}
.content-bottom{
	width:90%;
	margin:24px auto;
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
      <?php echo $logLink; ?>
      <?php echo $signUp_link; ?>
    </ul>
    &nbsp;<img src="images/sidebar_logo.png" alt="backburnr" style="width:94%;"/>
    <!-- end .sidebar1 --></div>
  <div class="content">
  <div class="banner">
  <h2 style="text-align:center; color:#CCC;">Social Netork Engineering Training Course and Most Raw In Your Face Social Experience On The Interweb</h2>
    <div class="topContent">
    <h2 style="text-align:center;">Get The Code</h2>
    	<img src="images/bracketts.png" alt="source files"/>
        <ul>
        <li>Get the source files</li>
        <li>Build your site</li>
        <li>Fork Us On Github</li>
        <li>Become a contributor</li>
        </ul>
        <p>
        All code for the training course is available on <a href="https://github.com/IsaacNeal/backburbr" target="_blank">github</a> and is updated frequently. Don't forget to check out <a href="http://www.worldofwebcraft.com" target="_blank">World Of Webcraft</a> for code help and advice, and to learn how to become a contributor to the project. Check back often for new updates!
        </p>
    </div>
    <div class="topContent">
    <h2 style="text-align:center">Watch The Videos</h2>
    	<img src="images/videosbtn.png" alt="videos" />
        <ul>
        <li style="list-style-type:none; text-align:center;">Learn To Code &darr;</li>
        <li>Registration and Login Systems</li>
        <li>Custom User Profiles</li>
        <li>Conversation systems</li>
        <li>Messaging systems</li>
        <li>Much Much More...</li>
        </ul>
        <p>Catch in depth video tutorials and learn to  program your own custom social community website from scratch. Don't forget to <a href="http://www.youtube.com/user/iPriceProductions">subscribe!</a></p>
    </div>
  </div>
  <div class="clearfloat"></div>
    <div class="content-bottom">
      <h2 style="text-align:center; color:#285C01;">Section 1 In Progress: Registration and Log In</h2>
    	<p>
        	The backburnr social engineering training course will be released in sections. Each section will contain multiple video lessons, each specific to the section. This system intended to be educational, and is offered <b>as is</b> with no warranties in the hopes that it will be useful. Check back for updates often as code will be updated daily. 
        </p>
    </div>
    <!-- end .content --></div>
    <div class="clearfloat"></div>
  <!-- end .container --></div>
<?php include_once("footer_template.php") ?>
</body>
</html>