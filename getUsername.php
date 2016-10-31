<?php
ini_set("session.cookie_httponly", 1);
if($_POST['token'] === $_SESSION['token']){
	header("Content-Type: application/json");
	session_start();
	if(isset($_SESSION['Username'])){
		$username = $_SESSION['Username'];
		echo json_encode(array(
		"success"=>true,
		"username"=>htmlentities($username)
		));
	}
	else{
		echo json_encode(array(
			"success"=>false
			));
	}
}
?>