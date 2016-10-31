<?php
ini_set("session.cookie_httponly", 1);
session_start();
	header("Content-Type: application/json");
	
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

?>