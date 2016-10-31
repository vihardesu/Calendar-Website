<?php
ini_set("session.cookie_httponly", 1);
header("Content-Type: application/json");
session_start();


$mysqli = new mysqli('localhost', 'calendarUser', 'vihar', 'calendar');
if($mysqli->connect_errno) {
	echo "failed";
	exit;
}
$id = $_POST['tdId'];



if(isset($id) && !empty($id) && isset($_SESSION['Username'])){

	$username = $mysqli->real_escape_string($_SESSION['Username']);
		if($username === "Master"){
			$stmt = $mysqli->prepare("SELECT eventId, title, date, time, user from events where date=?");
		if(!$stmt){
			echo json_encode(array(
				"success"=>false
				));
			exit;
		}
		$stmt->bind_param('s', $id);
		$stmt->execute();
		$stmt->bind_result($eId, $selectedTitle, $selectedDate, $selectedTime, $selectedUser);
		}
		else{
			$stmt = $mysqli->prepare("SELECT eventId, title, date, time, user from events where user=? and date=?");
			if(!$stmt){
				echo json_encode(array(
					"success"=>false
					));
				exit;
			}
			$stmt->bind_param('ss', $username, $id);
			$stmt->execute();
			$stmt->bind_result($eId, $selectedTitle, $selectedDate, $selectedTime, $selectedUser);
		}

	$totArray = array("success"=>true);
	while($row = $stmt->fetch()){
		$arr = array("eId"=>htmlentities($eId), "title"=>htmlentities($selectedTitle), "date"=>htmlentities($selectedDate), "time"=>htmlentities($selectedTime), "user"=>$selectedUser);
		array_push($totArray, $arr);
	}
	echo json_encode($totArray);
	$stmt->close();
	
}
else{
	echo json_encode(array(
		"success"=>false
		));
}



?>