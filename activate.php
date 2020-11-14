<?php include "partials/header.php"; ?>
<?php
if(isset($_GET["code"])){
	
    $link = htmlspecialchars(stripslashes(strip_tags($_GET["code"])));
	
	if($connect->connect_error){
		die("Connection failed: ".$connect->connect_error);
		exit;
	}
	elseif(!empty($link) && strlen($link)>0 && $link!=1){
		$statement = $connect->prepare("SELECT email_activation_link FROM users WHERE email_activation_link=?");
		$statement->bind_param("s", $link);
		$statement->execute();
		$statement->bind_result($db_link);
		$statement->fetch();
		$statement->close();
		
		if($db_link==$link){
			$statement = $connect->prepare("UPDATE users SET email_activation_link=1 WHERE email_activation_link=?");
			$statement->bind_param("s", $link);
			$statement->execute();
			$statement->close();
		
			echo "<p>Email was successfully activated.</p>";
		}
		else header("Location: index.php");
	}
	else header("Location: index.php");
}
else header("Location: index.php");
?> 

<?php include "partials/footer.php"; ?>