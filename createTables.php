<?php
require_once("scripts/connect.php");
$members_table_stmt = "CREATE TABLE IF NOT EXISTS members (
	id INT NOT NULL AUTO_INCREMENT,
	ext_id TEXT,
	username VARCHAR(16) NOT NULL,
	email VARCHAR(255) NOT NULL,
	password TEXT,
	lastlog DATETIME NOT NULL,
	signup_date DATETIME NOT NULL,
	activated ENUM('0','1') NOT NULL DEFAULT '0',
	avatar VARCHAR(255),
	banner VARCHAR(255),
	full_name VARCHAR(255),
	country VARCHAR(255),
	state VARCHAR(255),
	city VARCHAR(255),
	gender VARCHAR(12),
	birthday VARCHAR(255),
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
}
