<?php include_once "partials/header.php";?>
<?php
if(isset($user_name)){
	header("Location: index.php");
	exit;
}
else{
	$name = $error_name = "";
	$email = $error_email = "";
	
	$password = $error_password = "";
	$password_confirm = $error_password_confirm = "";
	
	$error = $sign_up = false;
	
	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sign_up'])){
		$name = htmlspecialchars(stripslashes(strip_tags($_POST["name"])));
		$email = strtolower($_POST["email"]);
		
		$password = $_POST["password"];
		$password_confirm = $_POST["password_confirm"];
		
		//check name
		if(empty($name) && strlen($name)==0){
			$error = true;
			$error_name = "Enter name.";
		}
		elseif(mb_strlen($name,"UTF-8")<3){
			$error = true;
			$error_name="Enterd name is short. (min. 3)";
		}
		elseif(mb_strlen($name,"UTF-8")>32){
			$error = true;
			$error_name="Enterd name is long. (max. 32)";
		}
		else{
			$statement = $connect->prepare("SELECT name FROM users WHERE name=?");
			$statement->bind_param("s", $name);
			$statement->execute();
			$statement->bind_result($db_name);
			$statement->fetch();
			$statement->close();
				
			if(strtolower($db_name)==strtolower($name)){
				$error = true;
				$error_name="Name is already in use.";
			}
		}
		//check email
		if(empty($email)){
			$error = true;
			$error_email = "Enter email.";
		}
		elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$error = true;
			$error_email = "Incorrect email format.";
		}
		else{
			$statement = $connect->prepare("SELECT email FROM users WHERE email=?");
			$statement->bind_param("s", $email);
			$statement->execute();
			$statement->bind_result($db_email);
			$statement->fetch();
			$statement->close();
				
			if($db_email==$email){
				$error = true;
				$error_email="Email is already in use.";
			}
		}
		//check password
		if(empty($password) && strlen($password)==0){
			$error = true;
			$error_password = "Enter password.";
		}
		elseif(mb_strlen($password,"UTF-8")<8){
			$error = true;
			$error_password="Enterd password is short. (min. 8)";
		}
		elseif(mb_strlen($password,"UTF-8")>32){
			$error = true;
			$error_password="Enterd password is long. (max. 32)";
		}
		elseif(
			preg_match( "/\d/", $password )!=1 ||
			preg_match("/[abcdefghijklmnopqrstuvwxyzáäčďéíĺňóôŕšťúýž]/", $password)!=1 ||
			preg_match("/[ABCDEFGHIJKLMNOPQRSTUVWXYZÁÄČĎÉÍĹŇÓÔŔŠŤÚÝŽĚŮŘ]/", $password)!=1 ||
			preg_match("/[^a-zA-Z0-9áäčďéíĺňóôŕšťúýžÁÄČĎÉÍĹŇÓÔŔŠŤÚÝŽěůřĚŮŘ]/", $password)!=1
		){
			$error = true;
			$error_password="Password must consist of length &lt;8,32&gt;<br> Passord must consist of at least 1:<br>- lowercase letter<br>- upppercase/capital letter<br>- digit number<br>- special character";
		}
		else{
			;
		}
		//check password confirm
		if(empty($password_confirm) && strlen($password_confirm)==0){
			$error = true;
			$error_password_confirm = "Enter confirm password.";
		}
		elseif($password != $password_confirm){
			$error = true;
			$error_password_confirm = "Passwords do not match.";
		}
		else{
			$error_password_confirm = "Enter confrim password again.";
		}
		//check is alright
		if($error==false){
			
			$email_activation_code = code_generator(8);
			$email_activation_link = hash("sha256", $email.$email_activation_code);
			$activated = 0;
			$password = hash("sha256", $password);
				
			$statement = $connect->prepare("INSERT INTO users (name, email, email_activation_link, password) VALUES (?, ?, ?, ?)");
			$statement->bind_param("ssss", $name, $email, $email_activation_link, $password);
			$statement->execute();
			$statement->close();
				
			$to = $email;
			$subject = "Sign-up";
			$headers = "From: no-reply@email.sk \r\n";
			$headers .= "MIME-Version: 1.0" . "\r\n"; 
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 

$message=' 
<html> 
	<head> 
		<title>Upload images</title> 
	</head> 
	<body> 
		<p>
			Successful sing up: <b>'.$name.'</b>
		</p>
		Do not forget to <a href="https://casinosim.eu/activate.php?code='.$email_activation_link.'">activate</a> your account. Or use code:'.$email_activation_code.'
	</body> 
</html>'; 

			mail($to, $subject, $message, $headers);
			$sign_up = "<p>You have successfully registered.<br>Activate your account via email link (but you must wait some time for mail).</p>";
		}
	}
	if($sign_up==false){
?>
		<h1>Sign up</h1>
        
        <form name="sign_up" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php if(!$error_name)echo $name;?>">
		<?php if($error_name){?><p class="form"><?php echo $error_name;?></p><?php }?>
        
        <label for="email">E-mail:</label><br>
        <input type="email" id="email" name="email" value="<?php if(!$error_email)echo $email;?>">
		<?php if($error_email){?><p class="form"><?php echo $error_email;?></p><?php }?>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
		<?php if($error_password){?><p class="form"><?php echo $error_password;?></p><?php }?>
        
        <label for="password_confirm">Confirm password:</label><br>
        <input type="password" id="password_confirm" name="password_confirm">
		<?php if($error_password_confirm){?><p class="form"><?php echo $error_password_confirm;?></p><?php }?>
        
        <input type="submit" value="Sign up" name="sign_up">
    	</form>
<?php
	}
	else echo $sign_up;
}
?>
<?php include_once "partials/footer.php"; ?>