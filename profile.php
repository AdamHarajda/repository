<?php include "partials/header.php"; ?>
<?php
if(!isset($user_name)){
	header("Location: profile.php");
	exit;
}
?>
	<div class="profile">
        <nav>
            <ul>
                <li><a href="profile.php?page=images">images</a></li>
                <li><a href="profile.php?page=upload">upload image</a></li>
            </ul>
        </nav>
    </div>
<?php
	if(isset($_GET["page"])){
		$page = $_GET["page"];
		switch($page){
			case "images":
				include("./images.php");
				break; 
			case "upload":
				include("./upload.php");
				break;
			default:
				include("./images.php");
	}
}
?>
<?php include "partials/footer.php"; ?>