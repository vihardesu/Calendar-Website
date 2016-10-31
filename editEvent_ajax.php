<?php
ini_set("session.cookie_httponly", 1);
session_start();
header("Content-Type: application/json");

if(isset($_SESSION['Username'])){
$mysqli = new mysqli('localhost', 'calendarUser', 'vihar', 'calendar');
	if($mysqli->connect_errno) {
		echo json_encode(array(
			"success"=>false
			));
		exit;
	}
}
if(isset($_SESSION['Username'])){
	$username = $_SESSION['Username'];
}
$eventId = $_POST['id'];
$newTitle = $mysqli->real_escape_string($_POST['newTitle']);
$newDate = $_POST['newDate'];
$newTime = $_POST['newTime'];

$sql = "UPDATE events SET title='$newTitle', date='$newDate', time='$newTime', user='$username' WHERE eventId=$eventId";


if(mysqli_query($mysqli, $sql)){
	echo json_encode(array(
		"success"=>true
		));
}
else{
	echo mysqli_error($mysqli);
	echo json_encode(array(
		"success"=>false,
		"message"=> "you suck"
		));
}

?>
