<?php
ini_set("session.cookie_httponly", 1);
header("Content-Type: application/json");
session_start();
$mysqli = new mysqli('localhost', 'calendarUser', 'vihar', 'calendar');
if($mysqli->connect_errno) {
	//printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}

$newUsername = $_POST['newUsername'];
$newPass = $_POST['newPass'];


if(isset($newUsername) && !empty($newUsername) && isset($newPass) && !empty($newPass)){
	//$password = crypt($_POST['newPass']); for aws server
	$password = crypt($_POST['newPass']);
	$newUser = $mysqli->real_escape_string($newUsername);

	//check if user already exists
	$userCheck = $mysqli->prepare("SELECT username FROM users");
		$userCheck->execute();
		$userCheck->bind_result($usernameCheck);
		while($userCheck->fetch()){
			if($usernameCheck == $newUsername){
				echo json_encode(array(
					"success" => false,
					"message" => "you big, big, bird"
					));
				exit;
			}
		}

	echo json_encode(array(
		"success" => true
		));

	//insert into db
	$stmt = $mysqli->prepare("insert into users (username, password) values (?, ?)");
	if(!$stmt){
		echo json_encode(array(
			"success" => false,
			"message" => "failure"
			));
		exit;
	}
	$stmt->bind_param('ss', $newUser, $password);
	$stmt->execute();
	$stmt->close();
	
}
else{
	echo json_encode(array(
		"success" => false,
		"message" => "Post not set"
		));
	exit;
}

?>