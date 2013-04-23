<?php
require_once("scripts/connect.php");
/* $members_table_stmt = "CREATE TABLE IF NOT EXISTS members (
	id INT NOT NULL AUTO_INCREMENT,
	username VARCHAR(16) NOT NULL,
	email VARCHAR(255) NOT NULL,
	password TEXT,
	lastlog DATETIME NOT NULL,
	signup_date DATETIME NOT NULL,
	activated ENUM('0','1') NOT NULL DEFAULT '0',
	ipaddress VARCHAR(255),
	PRIMARY KEY (id), 
	UNIQUE KEY username (username,email)
	
)";
if($db->query($members_table_stmt)){
	echo "<h2>Member Table Created Yay!</h2>";
}else{
	echo "<h2>There was an error creating the members table. Go back and check your code</h2>";
}
$activation_tbl_stmt = "CREATE TABLE IF NOT EXISTS activate (
	id INT NOT NULL AUTO_INCREMENT,
	user VARCHAR(255) NOT NULL,
	token VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
	
)";
if($db->query($activation_tbl_stmt)){
	echo "<h2>Activation Table Created Yay!</h2>";
}else{
	echo "<h2>There was an error creating the activation table. Go back and check your code</h2>";
} */
$enemies_tbl_stmt = "CREATE TABLE IF NOT EXISTS enemies (
	id INT NOT NULL AUTO_INCREMENT,
	user1 VARCHAR(255) NOT NULL,
	user2 VARCHAR(255) NOT NULL,
	user1_wins TEXT,
	user2_wins TEXT,
	date_connected DATETIME NOT NULL,
	PRIMARY KEY (id)
	
)";
$friends_tbl_stmt = "CREATE TABLE IF NOT EXISTS friends (
	id INT NOT NULL AUTO_INCREMENT,
	user1 VARCHAR(255) NOT NULL,
	user2 VARCHAR(255) NOT NULL,
	date_connected DATETIME NOT NULL,
	PRIMARY KEY (id)
	
)";
$enemy_disputes_tbl = "CREATE TABLE IF NOT EXISTS enemy_disputes (
	id INT NOT NULL AUTO_INCREMENT,
	user1 VARCHAR(255) NOT NULL,
	user2 VARCHAR(255) NOT NULL,
	dispute VARCHAR(255) NOT NULL,
	dispute_date DATETIME NOT NULL,
	PRIMARY KEY (id)
	
)";
$stalking_tbl_stmt = "CREATE TABLE IF NOT EXISTS stalking (
	id INT NOT NULL AUTO_INCREMENT,
	stalker VARCHAR(255) NOT NULL,
	victim VARCHAR(255) NOT NULL,
	stalker_email VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
	
)";
if($db->query($enemies_tbl_stmt)){
	echo "<h2>Enemies Table Created Yay!</h2>";
}else{
	echo "<h2>There was an error creating the Enemies table. Go back and check your code</h2>";
}


if($db->query($friends_tbl_stmt)){
	echo "<h2>Friends Table Created Yay!</h2>";
}else{
	echo "<h2>There was an error creating the Friends table. Go back and check your code</h2>";
}

if($db->query($enemy_disputes_tbl)){
	echo "<h2>Disputes Table Created Yay!</h2>";
}else{
	echo "<h2>There was an error creating the Disputes table. Go back and check your code</h2>";
}


if($db->query($stalking_tbl_stmt)){
	echo "<h2>Stalking Table Created Yay!</h2>";
}else{
	echo "<h2>There was an error creating the Stalking table. Go back and check your code</h2>";
}