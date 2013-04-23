<?php 
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