<?php
ini_set("session.cookie_httponly", 1);
header("Content-Type: application/json");
session_start();
$mysqli = new mysqli('localhost', 'calendarUser', 'vihar', 'calendar');
if($mysqli->connect_errno) {
	//printf("Connection Failed: %s\n", $mysqli->connect_error);
	echo json_encode(array(
	"success"=>false
	));
	exit;
}
$date = $mysqli->real_escape_string($_POST['date']);
$time = $mysqli->real_escape_string($_POST['time']);
$title = $mysqli->real_escape_string($_POST['title']);
if(isset($_SESSION['Username'])){
	$username = $_SESSION['Username'];
}
else{
	echo json_encode(array(
		"success"=>false
		));
	exit;
}



if(isset($_POST['date']) && !empty($_POST['date']) && isset($_POST['title']) && isset($_POST['title']) && isset($time) && !empty($time) && isset($_SESSION['Username']) && isset($_SESSION['Username'])){

	

	$stmt = $mysqli->prepare("insert into events (user, title, date, time) values (?, ?, ?, ?)");
	if(!$stmt){
		echo json_encode(array(
		"success"=>false,
		"message"=>$mysqli->error
		));
		//printf("Query Prep Failed: %s\n", $mysqli->error);

		exit;
	}
	$stmt->bind_param('ssss', $username, $title, $date, $time);
	$stmt->execute();
	$stmt->close();
	echo json_encode(array(
	"success"=>true,
	"date"=>htmlentities($date),
	"time"=>htmlentities($time)
	));

}




?>