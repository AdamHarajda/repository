<?php include 'partials/header.php'; ?>

<?php
if(isset($_GET["image"])){
	
    $link = htmlspecialchars(stripslashes(strip_tags($_GET["image"])));
	
	$statement = $connect->prepare("SELECT image FROM images WHERE link=?");
	$statement->bind_param("s", $link);
	$statement->execute();
	$statement->bind_result($db_image);
	$statement->fetch();
	$statement->close();
	
	if(isset($db_image)){
		echo '<img class="gallery" src="'.$db_image.'">';
	}
	else header("Location: index.php");
}
else{

	if(isset($user_name)){
?>
		<h1>logged in</h1>
<?php
	}
	else{
?>
		<h1>logged off</h1>
<?php
	}
}
?>
<?php include 'partials/footer.php'; ?>