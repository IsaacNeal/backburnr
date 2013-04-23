<?php
include_once("scripts/connect.php");
$num1 = "0";
$num2 = "0";
$num3 = "0";
$query1 = "SELECT id FROM voting_poll Where choice='1'"; 
$execute1 = $db->query($query1);
//$count1 = $query1->rowCount();
if($execute1->rowCount() > 0){
	$num1 = $execute1->rowCount();
}
$query2 = "SELECT id FROM voting_poll Where choice='2'"; 
$execute2 = $db->query($query2);
//$count2 = $query2->rowCount();
if($execute2->rowCount() > 0){
	$num2 = $execute2->rowCount();
}
$query3 = "SELECT id FROM voting_poll Where choice='3'"; 
$execute3 = $db->query($query3);
//$count3 = $query3->rowCount();
if($execute3->rowCount() > 0){
	$num3 = $execute3->rowCount();
}
$totalNums = "$num1, $num2, $num3";
echo "$totalNums";