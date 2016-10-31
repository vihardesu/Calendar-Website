<?php
ini_set("session.cookie_httponly", 1);
session_start();
if($_POST['token'] === $_SESSION['token']){
header("Content-Type: application/json");

if(isset($_SESSION['Username'])){
$mysqli = new mysqli('localhost', 'calendarUser', 'vihar', 'calendar');
	if($mysqli->connect_errno) {
		printf("Connection Failed: %s\n", $mysqli->connect_error);
		exit;
	}
	$eventId = $_POST['id'];

	//check username of the file
		$sql = ("Delete from events where eventId= $eventId");
		if(mysqli_query($mysqli, $sql)){
			echo json_encode(array(
				"success"=>true
				));
		}
		else{
			echo "Error deleting record: ".mysqli_error($mysqli);
		}
		
	}
}
?>