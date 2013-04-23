<?php
//include_once("scripts/connect.php");
include_once("scripts/check_user.php");
if($user_is_logged == true){
	header("location: index.php");
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
@media screen and (-webkit-min-device-pixel-ratio:0) {
    select {padding-right:6px}
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
  <div id="whole_page">
	<h2 style="text-align:center;">Warning! This site contains vicious dialog and verbal attacs.</h2>
    <p id="message_span">To register your Backnurnr.com account using your Google+ profile information click the "Authorize" button below to allow us to gather information. We will only gather your email address, public id, and basic details like first and last name. This data is used soley to identify you here on the site.
    <br /><p class="submit"><button id="authorize-button">Get your data from Google</button></p></p>
  <br />
  <div id="form" class="form">
    <strong style="text-align:center">Please verify the information below and click next.</strong>
    <br />
    <br />
<div id="signup_form">
<form id="gp_signup">
<label for="displayName"><strong>How your name will appear</strong>
<br />
<input type="text" id="displayName" name="displayName">
</label>
<br />
<label for="email"><strong>Your email</strong>
<br />
<input type="text" id="email" name="email">
</label>
<input type="hidden" id="verified_email" name="verified_email">
<input type="hidden" id="ext_id" name="ext_id">
<br />
<br />
<p class="submit">
<button id="signUpBtn" onclick="return false;" type="submit">Next</button>
</p>
</form>
</div>
</div>
</div>
  <br />
  </div>
</div>
    <div class="clearfloat"></div>
  <!-- end .container --></div>
<?php include_once("footer_template.php") ?>
<script type="text/javascript">
      var clientId = '707492613930.apps.googleusercontent.com';
      var apiKey = 'AIzaSyB9wpkWaLmOyvPiL7e3gQc3SgN63eG5K_s';
      var scopes = 'https://www.googleapis.com/auth/plus.me https://www.googleapis.com/auth/userinfo.email';

      function handleClientLoad() {
        gapi.client.setApiKey(apiKey);
        window.setTimeout(checkAuth,1);
      }

      function checkAuth() {
        gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: true}, handleAuthResult);
      }


      function handleAuthResult(authResult) {
        var authorizeButton = document.getElementById('authorize-button');
        if (authResult) {
          authorizeButton.style.visibility = 'hidden';
          makeApiCall();
        } else {
          authorizeButton.style.visibility = '';
          authorizeButton.onclick = handleAuthClick;
        }
      }

      function handleAuthClick(event) {
        gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: false}, handleAuthResult);
        return false;
      }

      function logResponse(resp) {
        console.log(resp);
      }

      function makeApiCall() {

        gapi.client.load('oauth2', 'v2', function() {
          var request = gapi.client.oauth2.userinfo.get();

          request.execute(function(logResponse){
			  document.getElementById('email').value = logResponse.email;
			  document.getElementById('verified_email').value = logResponse.verified_email;
			  document.getElementById('ext_id').value = logResponse.id;
			  console.log(logResponse.email);
			  console.log(logResponse.id);
		  });
        });
		
        gapi.client.load('plus', 'v1', function() { 
          var request = gapi.client.plus.people.get({ 
            'userId': 'me' 
          });

          request.execute(function(logResponse){
			 document.getElementById('displayName').value = logResponse.displayName;
			 document.getElementById('message_span').style.display = 'none';
			  //console.log(logResponse.displayName+'|'+logResponse.email+'|'+logResponse.image.url+'|'+email);
			  console.log(logResponse.image.url);
			  console.log(logResponse.email);
			  console.log(logResponse);
		  });
        });
      }
    </script>
    <script src="https://apis.google.com/js/client.js?onload=handleClientLoad">
    </script>
    <script>
document.getElementById('signUpBtn').onmousedown = function(){
ajax('gp_signup',
	 'POST', 
	 'ext_signup.php', 
	 'signup_form'
)};
</script>
</body>
</html>
