<?php 
$cbOutput = "";
$rad = "";
if(isset($_POST['name'])){
	$name = $_POST['name'];
	$address = $_POST['address'];
	$number = $_POST['number'];
	$email = $_POST['email'];
	$select = $_POST['select'];
	$textarea = $_POST['textarea'];
	if(isset($_POST['cbs']) && $_POST['cbs'] != ""){
	$cbs = json_decode($_POST['cbs']);
	foreach($cbs as $key=>$value){
		$cb = $key + 1;
		$cbOutput .= "Checkbox".$cb.": ".$value."<br />";
} 
	
	}else{
		$cbOutput = "None";
	}
	if(isset($_POST['rad1'])){
		$rad = $_POST['rad1'];
	} else if(isset($_POST['rad2'])){
		$rad = $_POST['rad2'];
	} else if(isset($_POST['rad3'])){
		$rad = $_POST['rad3'];
	}else{
		$rad = "None";
	}
	
	$output = "Name: $name<br />Address: $address<br />Phone Number: $number<br />
	Email: $email<br />You: $select<br />Checkboxes selected:<br /> $cbOutput <br />Radio: $rad<br />Message:<br />$textarea";
	echo $output;
}
if(isset($_GET['welcome'])){
	$welcome = $_GET['welcome'];
	$my_framework = $_GET['my_framework'];
	$output = "$welcome<br />My Framework: $my_framework";
	echo $output;
}
?>