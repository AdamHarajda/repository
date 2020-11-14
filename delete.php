<?php include "partials/header.php"; ?>
<?php
if(!isset($user_name)){
	header("Location: index.php");
	exit;
}
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])){
		
		$id = $_POST["delimg"];
		
		$sql="DELETE FROM images WHERE imageID=".$id."";
		
		if($connect->query($sql) == TRUE){
			echo "Image successfuly deleted.";
		}
		else{
			echo "Error deleting record: ".$connect->error;
		}
}
?> 

<?php include "partials/footer.php"; ?>