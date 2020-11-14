<?php

$host = "localhost";
$username = "root";
$password = "admin";
$dbname = "usersdata";

$connect = new mysqli($host, $username, $password, $dbname);

if($connect->connect_error){
	die("Connection failed: ".$connect->connect_error);
}

function code_generator($length){
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $charactersLength = strlen($characters);
    $randomGenerate = "";
    for($i=0;$i<$length;$i++){
        $randomGenerate .= $characters[rand(0,$charactersLength-1)];
    }
    return $randomGenerate;
}
?>