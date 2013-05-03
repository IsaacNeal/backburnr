<?php 
/*
* @author Isaac Price
* This script is not part of the Backburnr lesson series but it is in the site and the script is available for you here
* This script is part of an application which requires a few files, all can be found in the source code here on github 
* for the backburnr project.
* The other files are: js/poll.js and getNums.php
*/
function PDOBindArray(&$poStatement, &$paArray){
 	foreach ($paArray as $k=>$v){
	$poStatement->bindValue(':'.$k,$v);
	} // foreach
	//return $binded;
}
if(isset($_POST['radio'])){
	if($_POST['radio'] == "undefined"){
		echo "Please make a selection";
		exit();
	}
	include("scripts/connect.php");
	$sql = $db->prepare("SELECT id FROM voting_poll WHERE ipaddress=:ipaddress");
	$value = $_POST["radio"];
	
	$ipaddress = getenv('REMOTE_ADDR');
	$sql->bindValue(':ipaddress',$ipaddress,PDO::PARAM_STR);
	//// execute command ////
	$sql->execute();
	if($sql->rowCount() > 0){
		echo "Sorry, You have already voted in this particular poll.";
		exit();
	}
	$insertSQL = $db->prepare("INSERT INTO voting_poll (ipaddress, choice) 
	VALUES (:ipaddress, :value)");
	$ipaddress = getenv('REMOTE_ADDR');
	$value = preg_replace('#[^0-9]#i', '', $_POST['radio']);
	$arr = array(
		'ipaddress'=>$ipaddress,
		'value'=>$value
	);
	PDOBindArray($insertSQL, $arr);
	$insertSQL->execute();
	echo "Thanks! Your vote has been tallied.";
	exit();
}
