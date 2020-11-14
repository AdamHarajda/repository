<?php
if(!isset($user_name)){
	header("Location: index.php");
	exit;
}
else{
	$name = $error_name = "";
	$file = $error_file = "";
	
	$error = $upload = false;
	
	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload'])){
		
		$name = htmlspecialchars(stripslashes(strip_tags($_POST["name"])));
		
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
			$statement = $connect->prepare("SELECT name FROM images WHERE name=?");
			$statement->bind_param("s", $name);
			$statement->execute();
			$statement->bind_result($db_name);
			$statement->fetch();
			$statement->close();
				
			if(strtolower($db_name)==strtolower($name)){
				$error = true;
				$error_name="Name is already in use.";
			}
			else{
				if(isset($_FILES['file'])){
					/*
					$file_image = getimagesize($_FILES["file"]["name"]);
					if($file_image == false){
						$error = true;
						$error_file="File is not an image.";
					}
					*/
					$target_dir = "assets/images/";
					$target_file = $target_dir . basename($_FILES["file"]["name"]);
					$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
					
					if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ){
						$error = true;
						$error_file="Valid type: .jpg, .jpeg, .png, .gif";
					}
					
					if($_FILES["file"]["size"] > 500000){
						$error = true;
						$error_file="Image is too large.";
					}
				}
			}
		}
		//check is alright
		if($error==false){
			$image = $_FILES['file'];
			$code = code_generator(8);
			$link = hash("sha256", $user_name.$name.$code);
			
			
			//Convert to base64 
			$image_base64 = base64_encode(file_get_contents($_FILES['file']['tmp_name']) );
			$image = 'data:image/'.$imageFileType.';base64,'.$image_base64;
			
			$statement = $connect->prepare("SELECT userID FROM users WHERE name=?");
			$statement->bind_param("s", $user_name);
			$statement->execute();
			$statement->bind_result($db_userID);
			$statement->fetch();
			$statement->close();
				
			$statement = $connect->prepare("INSERT INTO images (name, link, image, userID) VALUES (?, ?, ?, ?)");
			$statement->bind_param("sssi", $name, $link, $image, $db_userID);
			$statement->execute();
			$statement->close();
			
			echo "<p class=\"ok\">uploaded</p>";
		}
	}
?>

		<form name="upload" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]."?page=upload");?>" method="post" enctype="multipart/form-data">
    
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="">
        <?php if($error_name){?><p class="form"><?php echo $error_name;?></p><?php }?>
        
        <label for="name">Select image to upload:</label>
        <input type="file" name="file" id="file">
        <?php if($error_file){?><p class="form"><?php echo $error_file;?></p><?php }?>
        
        <input type="submit" value="Upload" name="upload">
    	</form>

<?php
}
?>