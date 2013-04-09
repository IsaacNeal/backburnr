<?php include_once("../scripts/check_user.php"); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>You shall not pass!</title>
<link rel="stylesheet" href="../style/style.css"/>
<style type="text/css">
.banner{
	margin: 0px auto;
	width:78%;
	color:#333;
	text-shadow: 0px -1px #000000;
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
  <div class="banner">
  <h1 style="text-align:center; color:#F00;">This page is forbidden!</h1>
  <p style="text-align:center;">
        	You have navigated to a page that you are not authorized to view. If this is a mistake please contact the administrator and we will dispatch a team of highly trained monkeys to fix this error. 
        </p>
  </div>
  <!---------------------->
  <div style="width:100%;">
    <div style="float:left; width:20%; height:200px;"></div>
    <div style="float:left; width:58%;"><img src="../images/403.png" alt="nopass" style="width:100%;"/></div>
    </div>
    <div class="clearfloat"></div>
    <!-- end .content --></div>
    <div class="clearfloat"></div>
  <!-- end .container --></div>
<?php include_once("../footer_template.php") ?>
</body>
</html>