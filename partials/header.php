<?php
ob_start();
session_start();

if(isset($_SESSION["name"])){
	$user_name = $_SESSION["name"];
}
include_once "resources/config.php";

?>
<!DOCTYPE html>
<html lang="sk">
	<head>
    	<title>Upload images</title>
    	<meta name="title" content="Upload images"/>
    	<meta charset="UTF-8"/>
        <meta name="author" content="Adam Harajda" />
        <meta name="keywords" content="upload, share images" />
        <meta name="description" content="Upload and share you images for free" />
        
        <link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Noto+Sans&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="./assets/css/css.css" type="text/css" />
    </head>
    <body>
    <header>
        <div class="menu">
<?php
        if(isset($user_name)){
?>
		<nav>
            <ul>
                <li><a href="profile.php"><?php echo $user_name; ?></a></li>
                <li><a href="sign-out.php" class="off">Sign out</a></li>
            </ul>
        </nav>
<?php
        }
        else{
?>
        <nav>
            <ul>
                <li><a href="sign-up.php">Sign up</a></li>
                <li><a href="sign-in.php">Sign in</a></li>
            </ul>
        </nav>
<?php
        }
?>
    	</div>
    </header>
    <section>
        <main>