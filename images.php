<?php
if(!isset($user_name)){
	header("Location: profile.php");
	exit;
}

$statement = $connect->prepare("SELECT userID FROM users WHERE name=?");
$statement->bind_param("s", $user_name);
$statement->execute();
$statement->bind_result($db_userID);
$statement->fetch();
$statement->close();

$sql = "SELECT imageID, name, link, image FROM images WHERE userID=".$db_userID."";
$result = mysqli_query($connect, $sql);

if(mysqli_num_rows($result) > 0){
	while($row = mysqli_fetch_assoc($result)){
?>
		<div class="img">
        <p ><?php  echo "<b>Name:</b> ".$row['name']; ?> </p>
        <p ><?php echo "<b>Share link:</b> index.php?image=".$row['link']; ?></p>
		<img class="gallery" src="<?php echo $row['image'] ?>">
        <form name="delete" action="<?php echo htmlspecialchars("delete.php");?>" method="post" >
        	<input for="delimg" type="hidden" id="delimg" name="delimg" value="<?php echo $row['imageID']; ?>">
        	<input type="submit" value="Delete" name="delete">
    	</form>
        </div>
<?php	
	}
}
else echo "empty gallery";

$connect->close();
?>