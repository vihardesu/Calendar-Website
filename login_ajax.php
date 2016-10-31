<?php
header("Content-Type: application/json");

$mysqli = new mysqli('localhost', 'calendarUser', 'vihar', 'calendar');
if($mysqli->connect_errno) {
	//printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
$username = $_POST['username'];
$password = $_POST['password'];

if(isset($username) && !empty($username) && isset($password) && !empty($password)){
	$stmt = $mysqli->prepare("SELECT COUNT(*), username, password FROM users WHERE username=?");
	// Bind the parameter
	$user = $mysqli->real_escape_string($_POST['username']);
	$stmt->bind_param('s', $user);
	
	$stmt->execute();
	 
	// Bind the results
	$stmt->bind_result($cnt, $user, $pwd_hash);
	$stmt->fetch();
	
	$pwd_guess = trim(htmlentities($_POST['password']));
	// Compare the submitted password to the actual password hash
	if($cnt==1 && crypt($pwd_guess, $pwd_hash)==$pwd_hash){
		// Login succeeded!
		ini_set("session.cookie_httponly", 1);
		session_start();
		$_SESSION['Username'] = $user;
		$_SESSION['token'] = substr(md5(rand()), 0, 10);
		echo json_encode(array(
		"success" =>true,
		"username"=>htmlentities($user)
		));
	exit;
	}
	else{
		echo json_encode(array(
		"success" => false,
		"message" => "didn't work"
		));
	exit;
	}
}




?>