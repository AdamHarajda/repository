<?php

$host = "localhost";
$username = "root";
$password = "admin";
$dbname = "usersdata";

$connect = new mysqli($host, $username, $password, $dbname);

if($connect->connect_error){
	die("Connection failed: ".$connect->connect_error);
}

$sql=("CREATE TABLE users (
	userID BIGINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
	name VARCHAR(64) NOT NULL,
	email VARCHAR(255) NOT NULL,
	email_activation_link VARCHAR(64),
	password VARCHAR(64) NOT NULL
	)");
if($connect->query($sql) === TRUE){
	echo "Table users created successfully";
}
else{
	echo "Error creating table: ".$connect->error;
}

$sql=("CREATE TABLE images (
	imageID BIGINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
	name VARCHAR(32) NOT NULL,
	link VARCHAR(64) NOT NULL,
	image LONGBLOB NOT NULL,
	userID BIGINT UNSIGNED,
	FOREIGN KEY (userID) REFERENCES users(userID)
	);");
if($connect->query($sql) === TRUE){
	echo "Table images created successfully";
}
else{
	echo "Error creating table: ".$connect->error;
}

$connect->close();

?>