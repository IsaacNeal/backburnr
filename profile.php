<?php
$msg = "";
if(isset($_GET['msg'])){
	$msg = preg_replace('#[^a-z0-9%]#i', '', $_GET['user']);
	$msg = str_replace('%20', ' ', $msg);
}
$po_id =
$avatar = "";
$fullName = "";
$location = "";
$birthday = "";
$friends_count = "";
$enemies_count = ""; 
$username = "";
$banner = "";
include_once("scripts/check_user.php");
if(!isset($_GET['user']) || $_GET['user'] == ""){
	header("location: oops.php?msgcode=p1");
	$db = null;
	exit();
}
if($user_is_logged != true){
	header("Location: oops.php?msgcode=p2");
	$db = null;
	exit();
}
$pageowner = preg_replace('#[^a-z0-9]#i', '', $_GET['user']);
$stmt = $db->prepare("SELECT id, ext_id, username, full_name, avatar, banner FROM members WHERE username=:pageowner AND activated='1' LIMIT 1");
$stmt->bindValue(':pageowner',$pageowner,PDO::PARAM_STR);
	try{
		$stmt->execute();
	}
	catch(PDOException $e){
		//echo $e->getMessage();
		print_r($e->getTrace());
		$db = null;
		exit();
	}
$user_exists = $stmt->rowCount();
if($user_exists == 0){
	header("location: oops.php?msgcodde=p1");
}
function isPageOwner($user, $pagename){
	if($user == $pagename){
		return true;
	}else{
		return false;
	}
}
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$po_id = $row['id'];
	$ext_id = $row['ext_id'];
	$username = $row['username'];
	$avatar = $row['avatar'];
	$fullName = $row['full_name'];
	$banner = $row['banner'];
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Backburnr Social Network Engineering Training Course</title>
<link rel="stylesheet" href="style/profile.css"/>
<script type="text/javascript" src="js/serialize.js"></script>
<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript">
window.onload = function(){
	var start_date = 2013;
		for(var i=start_date; i>1900; i--){
			document.getElementById('birthyear').innerHTML += '<option value='+i+'>'+i+'</option>';
	}
}
function details_init(e){
var details = document.getElementById('details').id;
var settings = document.getElementById('settings').id;
var privacy = document.getElementById('privacy').id;
var detailsMenu = [details,settings,privacy];
console.log(detailsMenu);
console.log(e);
var target = document.getElementById(e).id;
navmenus(target,detailsMenu);
}
function uploads_init(e){
var avatar_div = document.getElementById('avatar_upload').id;
var banner_div = document.getElementById('banner_upload').id;
var detailsMenu = [avatar_div,banner_div];
console.log(detailsMenu);
console.log(e);
var target = document.getElementById(e).id;
navmenus(target,detailsMenu);
}
function navmenus(x, ma){
	for (var m in ma) {
		if(ma[m] != x){
			document.getElementById(ma[m]).style.display = "none";
		}
	}
	if(document.getElementById(x).style.display == 'block'){
		document.getElementById(x).style.display = "none";
	}
	else{
		document.getElementById(x).style.display = "block";
	}
}
</script>
<script>
function show_lightbox(){
	document.getElementById('light').style.display = 'block';
	document.getElementById('fade').style.display = 'block';
}
function hide_lightbox(){
	document.getElementById('light').style.display = 'none';
	document.getElementById('fade').style.display = 'none';
}
</script>
<style type="text/css">
.banner{
	margin-top:0px;
	width:94%;
	height:272px;
	color:#333;
	text-shadow: 0px -1px #000000;
	margin-left:36px;
}
.banner_bottom{
	width:94%;
	text-shadow: 0px -1px #000000;
	border-bottom-left-radius:6px;
	-moz-border-radius-bottomleft:6px;
	border-bottom-right-radius:6px;
	-moz-border-radius-bottomright:6px;
	background:url(images/textureblock.png) repeat;
	margin-left:36px;
	height:62px;
}
.banner_bottom ul li{
	width:200px;
	text-align:left;
}
.avatar{
	width:100%; 
	height:200px; 
	background:#666;
	padding:3px;
	border-radius:3px;
	-moz-border-radius:3px;
	box-shadow: 2px 2px 3px #999;
	margin-top:36px;
	margin-left:12px;
}
.avatar img{
	width:100%;
	height:100%;
}
.banner img{
	width:100%;
	height:100%;	
}
.topHeaderimg1{
	background:url(images/code.png) no-repeat;
	background-size:100%;
	width:28%;
	height:200px;
	min-height:480px;
	float: left;
	text-align:center;
	margin-left:24px;
}
.topHeaderimg2{
	clear:right;
	background:url(images/video.png) no-repeat;
	background-size:100%;
	width:28%;
	height:200px;
	min-height:480px;
	float: left;
	text-align:center;
	margin-left:24px;
}
.topHeaderText{
	width:58%;
	float:left;
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
	background-color:#F8F8F8;
}
.contentTop p a:link{
	color: #287315;
	text-decoration: none;
}
.contentTop p a:hover, a:active, a:focus{
	color: #287315;
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
.topsection{
	float:left;
	width:58%; 
	box-shadow: 1px 1px 6px #333 inset;
	border-radius:6px;
	-moz-border-radius:6px;
	padding-top:12px;
	background:#F8F8F8;
}
.user-details{
	padding-top:16px;
	width:100%;
	height:62px;
	background:url(images/textureblock.png) repeat;
	box-shadow: 3px 3px 5px #333 inset;
	border-bottom:#333 2px thin solid;
	color:#CCC;
}
.user-details h2{
	text-shadow: -1px -1px #000000;
	display:inline;
}
/* .styled-select select {
   background: transparent;
   width: 68px;
   padding: 5px;
   font-size: 16px;
   line-height: 1;
   border: none;
   outline:none;
   border-radius: 0;
   height: 27px;
   -webkit-appearance:none;
    -moz-appearance:none;
    appearance:none;
    cursor:pointer;
   box-shadow: 3px 3px 5px #CCC inset;
   -moz-box-shadow:3px 3px 5px #CCC inset;
   -webkit-box-shadow:3px 3px 5px #CCC inset;
} */

.styled-select select {
    padding:6px;
    margin: 0;
    -webkit-border-radius:4px;
    -moz-border-radius:4px;
    border-radius:4px;
    -webkit-box-shadow: 0 3px 0 #ccc, 0 -1px #fff inset;
    -moz-box-shadow: 0 3px 0 #ccc, 0 -1px #fff inset;
    box-shadow: 0 3px 0 #ccc, 0 -1px #fff inset;
    background: #f8f8f8;
    color:#888;
    border:none;
    outline:none;
    display: inline-block;
    -webkit-appearance:none;
    -moz-appearance:none;
    appearance:none;
    cursor:pointer;
	width:72px;
	height: 27px;
}


/* Targetting Webkit browsers only. FF will show the dropdown arrow with so much padding. */
@media screen and (-webkit-min-device-pixel-ratio:0) {
    select {padding-right:18px}
}

.styled-select label {position:relative}
.styled-select label:after {
    content:'<>';
    font:11px "Consolas", monospace;
    color:#aaa;
    -webkit-transform:rotate(90deg);
    -moz-transform:rotate(90deg);
    -ms-transform:rotate(90deg);
    transform:rotate(90deg);
    right:8px; top:2px;
    padding:1 1 2px;
    border-bottom:1px solid #ddd;
    position:absolute;
    pointer-events:none;
}
.styled-select label:before {
    content:'';
    right:6px; top:0px;
    width:20px; height:20px;
    background:#f8f8f8;
    position:absolute;
    pointer-events:none;
    display:block;
}
.banner-upload{
	float:left;
	position:absolute;
	top:18%;
	left:80%;
}

.page-uploads{
	text-align:left;
	margin-left:8px;
	margin-top:68px;
	width: 89.8%;
	max-width: 100%;
	border-radius:3px;
	-moz-border-radius:3px;
	padding:6px;
	padding-left:10px;
	padding-bottom:26px;
	color:#FFF;
	background-color:#333;
}
.black_overlay{
	display: none;
	position: absolute;
	top: 0%;
	left: 0%;
	width: 100%;
	height: 100%;
	background-color: black;
	z-index:1001;
	-moz-opacity: 0.8;
	opacity:.80;
	filter: alpha(opacity=80);
}

.white_content {
	display: none;
	position: absolute;
	top: 25%;
	left: 25%;
	width: 50%;
	height: 50%;
	padding: 16px;
	border: 16px solid #A7A7A7;
	border-radius:12px;
	-moz-border-radius:12px;
	background-color: white;
	z-index:1002;
	overflow: auto;
}
/* .styled-select {
   width: 74px;
   height: 27px;
   overflow: hidden;
   background: url(images/selectTick.png) no-repeat right #F8F8F8;
   border: 1px solid #ccc;
   border-radius:3px;
	-moz-border-radius:3px;
} */
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
<div class="user-details">
<div style="width:600px; text-align:left; padding:16px; float:left;">
<?php if ($fullName != ''): ?>
<h2><strong><?php echo $fullName; ?></strong></h2>
<?php else: ?>
<h2><strong><?php echo $username; ?></strong></h2>
<?php endif; ?>  
</div>
</div>
<!--<div class="clearfloat" style="height:0px;"></div> -->
  <div class="sidebar1" style="clear:both;">
  <div class="avatar">
  <?php if ($avatar != '' && file_exists("members/$po_id/$avatar")): ?>
  	<img src="members/<?php echo $po_id.'/'.$avatar ?>" alt="<?php echo $username ?>" />
  <?php else: ?>
  	<img src="images/default_avatar.png" alt="<?php echo $username ?>" />
  <?php endif; ?>
  </div>
   <?php if(isPageOwner($log_uname,$pageowner)): ?>
   <ul class="nav" style="margin-top:26px;">
   	<li>
    <a href="#" onclick="show_lightbox();">Edit Avatar/Banner <img src="images/tickdown.png" alt="toggle"/></a>
    </li>
   </ul>
   <?php else: ?>
   <ul class="nav" style="margin-top:26px;">
   	<li>
    <a href="#" onclick="return false;" onmousedown="showToggle('interactnav');">Interact <img src="images/tickdown.png" alt="toggle"/></a>
    </li>
   </ul>
   <?php endif; ?>
   
   <?php if(isPageOwner($log_uname,$pageowner)): ?>
   <div id="light" class="white_content">
   <div id="graphics-uploads">
   <strong style="color:#F00; float:right;"><a href="#" onclick="return false;" onmousedown="hide_lightbox()">Cancel</a></strong>
  <p class="submit" style="float:left;"><button type="button" onclick="uploads_init('avatar_upload')">Edit Avatar</button></p>
  <p class="submit" style="float:left;"><button type="button" onclick="uploads_init('banner_upload')">Edit Banner</button></p>
  <div class="page-uploads" id="avatar_upload" style="display:none; clear:both;">
<form action="profWrite.php" method="post" enctype="multipart/form-data" name="myform">
	<strong>Upload an avatar</strong>
  <input style="none;" name="avatar" type="file">
    <br class="clearfloat" />
    Our system will automatically re size your image, but for best results your image should be between 180 to 200 pixels wide and 180 to 200 pixels in height.
  <p class="submit"><button type="submit">Upload</button></p>
</form>
  </div>
  <div class="page-uploads" id="banner_upload" style="display:none; clear:both;">
<form action="profWrite.php" method="post" enctype="multipart/form-data" name="myform">
	<strong>Upload a Banner</strong>
  <input style="none;" name="banner" type="file">
    <br />
    Our system will automatically re size your image, but for best results your image should be between 400 to 600 pixels wide and 180 to 272 pixels in height.
  <p class="submit"><button type="submit">Upload</button></p>
</form>
  </div>
 </div>
</div>
<div id="fade" class="black_overlay"></div>
  <?php endif; ?>
  <?php if(isPageOwner($log_uname,$pageowner)): ?>
   <ul class="nav" style="margin-top:68px;">
      <li><a class="edit-details" href="#" onclick="return false;" onmousedown="details_init('details')">Your Details</a></li>
      <li><a class="edit-details" href="#" onclick="return false;" onmousedown="details_init('settings')">Settings</a></li>
      <li><a class="edit-details" href="#"onclick="return false;" onmousedown="details_init('privacy')">Privacy</a></li>
      <li><a href="#">Inbox</a></li>
    </ul>
    <div id="details" style="display:none">
    <form id="edit_details" class="form" onSubmit="return false;">
     <em>Full Name:</em>
     <br />
       <input type="text" name="fullname" id="fullname">
     <em>Country</em>
       <input type="text" name="country" id="country">
     <em>State/Province</em>
       <input type="text" name="state" id="state">
     <em>City/Town</em>
       <input type="text" name="city" id="city">
       <br />
    <em>Birth Date</em>
     <br />
     <div style="float:left;">
     <input type="text" style="width:28px; height:9px; margin-bottom:8px;" id="birthmonth" placeholder="m/m"> 
     <input type="text" style="width:22px; height:9px; margin-right:8px;" id="birthday" placeholder="d/d">
     </div>
     <div class="styled-select"><label><select id="birthyear"></select></label></div>
     <div class="clearfloat"></div>
     <div style="float:left;">
     <em>Gender:</em>
     <div class="styled-select">
     <label>
     <select id="gender">
       <option value=""></option>
       <option value="female">Female</option>
       <option value="male">Male</option>
     </select>
     </label>
     </div>
     </div>
     <div style="float:left; padding-left:24px; padding-bottom:16px;">
     <br />
     <span class="submit"><button id="detailsForm" type="button">Update</button></span>
     <div id="details_status"></div>
     </div>
     <br class="clearfloat" />
    </form>
    </div>
<script>
document.getElementById('detailsForm').onclick = function(){
ajax('edit_details',
	 'POST', 
	 'profWrite.php', 
	 'details_status'
)};
</script>
    <div id="settings" style="display:none;">
    This is for editing settings
    </div>
    <div id="privacy" style="display:none;">
    This is for editing privacy
    </div>
    <?php else: ?>
    <div id="interactnav" style="display:none">
    <ul class="nav" style="margin-bottom:68px;">
      <li><a href="#">Add Friend</a></li>
      <li><a href="#">Make Enemy</a></li>
      <li><a href="#">Follow</a></li>
      <li><a href="#">Block <?php echo $pageowner ?></a></li>
    </ul>
    </div>
    <?php endif; ?>
</div>
  <div class="content">
  <div class="banner">
  <?php if ($banner != '' && file_exists("banners/$po_id/$banner")): ?>
  <img src="banners/<?php echo $po_id.'/'.$banner ?>" alt="no banner" />
  <?php else: ?>
  <img src="images/banner_default.png" alt="no banner" />
  <?php endif; ?>
  </div>
  <div class="banner_bottom">
	<ul class="nav2">
      <li><a href="#"><img src="images/combubSmall.png" alt="posts"/>Posts</a></li>
      <li><a href="#"><img src="images/aboutsmall.png" alt="about"/>About</a></li>
    </ul>
</div>
  <!--<div style="width:100%; margin-top:16px;">
    <div style="float:left; width:28%;padding-left:12px;"><img src="images/code.png" alt="p1" style="width:100%;"></div>
    <div class="topsection"><p>All code for the training course is available on <a href="https://github.com/IsaacNeal/backburnr" target="_blank">github</a> and is updated frequently. Don't forget to check out <a href="http://www.worldofwebcraft.com" target="_blank">World Of Webcraft</a> for code help and advice, and to learn how to become a contributor to the project. Check back often for new updates!</p></div>
    </div>
    <div style="width:100%; clear:both; padding-top:36px;">
    <div style="float:left; width:28%;padding-left:12px;"><img src="images/video.png" alt="p1" style="width:100%;"></div>
    <div class="topsection"><p>Catch in depth video tutorials and learn to program your own custom social community website from scratch. Video lessons will cover a wide range of programming techniques and logic. The system will be built using open source technologies like PHP, MySQL, JavaScript, HTML, and CSS. Don't forget to <a href="http://www.youtube.com/user/iPriceProductions">subscribe</a> to get alerts when new videos are released!</p></div>
    </div> -->
    <!--<div class="topContent">
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
  
  <div class="clearfloat"></div> -->
    <div class="clearfloat"></div>
    <!--<div class="content-bottom">
      <h2 style="color:#285C01; text-align:center;">Section 1 In Progress: Registration and Log In</h2>
    	<p>
        	The backburnr social engineering training course will be released in sections. Each section will contain multiple video lessons, each specific to the section. This system is intended to be educational, and is offered <b>as is</b> with no warranty in the hopes that it will be useful. Check back for updates often as code will be updated daily. 
        </p>
    </div> -->
    <!-- end .content --></div>
    <div class="clearfloat"></div>
  <!-- end .container --></div>
<?php include_once("footer_template.php") ?>
</body>
</html>