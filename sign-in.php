<?php include_once "partials/header.php"; ?>
<?php
if(isset($user_name)){
	header("Location: profile.php");
	exit;
}
else{
	$email = $error_email = "";
	$password = $error_password = "";
	$error = false;
	
	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sign_in'])){
		$email = strtolower($_POST["email"]);
		$password = $_POST["password"];
		
		//check email
		if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
			$error = true;
			$error_email = "Enter email.";
		}
		else{				
			$statement = $connect->prepare("SELECT email FROM users WHERE email=?");
			$statement->bind_param("s", $email);
			$statement->execute();
			$statement->bind_result($db_email);
			$statement->fetch();
			$statement->close();
				
			if($db_email!=$email){
				$error = true;
				$error_email="Incorrect mail.";
			}
			else{
				//check password
				$password = hash("sha256", $password);
					
				$statement = $connect->prepare("SELECT password FROM users WHERE email=?");
				$statement->bind_param("s", $db_email);
				$statement->execute();
				$statement->bind_result($db_password);
				$statement->fetch();
				$statement->close();
					
				if($db_password!=$password){
					$error = true;
					$error_password="Incorrect password.";
				}
			}
		}
		//check only password
		if((empty($password) && strlen($password)==0)){
			$error = true;
			$error_password = "Enter password.";
		}
		//
		if($error==false){
			
			$statement = $connect->prepare("SELECT name FROM users WHERE email=?");
			$statement->bind_param("s", $email);
			$statement->execute();
			$statement->bind_result($db_name);
			$statement->fetch();
			$statement->close();
			
			$_SESSION["name"] = $db_name;
			header("Location: index.php");
		}
	}
}
?>
<form name="sign_in" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        
    <label for="email">E-mail:</label><br>
    <input type="email" id="email" name="email" value="<?php if(!$error_email)echo $email;?>">        
    <?php if($error_email){?><p class="form"><?php echo $error_email;?></p><?php }?>
            
    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password">
    <?php if($error_password){?><p class="form"><?php echo $error_password;?></p><?php }?>
            
    <input type="submit" value="Sign in" name="sign_in">
        
</form>
<?php include_once "partials/footer.php"; ?>