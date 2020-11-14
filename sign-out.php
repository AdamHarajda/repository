<?php include 'partials/header.php'; ?>
<?php
if($_SESSION["name"]){
	session_destroy();
	header("Location: index.php");
}
?>
<?php include 'partials/footer.php'; ?>