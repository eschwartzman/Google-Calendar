<?php
//HTTP only
ini_set("session.cookie_httponly", 1);
require 'database1.php';
if(true){
	$name = $mysqli->real_escape_string($_POST['username']);
	$shared_person = $mysqli->real_escape_string($_POST['shared_person']);
	$shared_event = $mysqli->real_escape_string($_POST['shared_event']);
	//Text must be inputted
	if((!preg_match('/^[\w_\-]+$/',$shared_person)) || (!preg_match('/^[\w_\-]+$/',$shared_event))){
		echo json_encode(array(
			"blank" => true
			));
		exit;
	}
	//select specific event
	$stmt = $mysqli->prepare("SELECT  `title`, `date`, `time`, `catagory`, `month` FROM events WHERE user=? and title=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('ss', $name, $shared_event);
	$stmt->execute();
	$stmt->bind_result($title7, $date7, $time7, $catagory7, $month7);
	$stmt->fetch();
	$stmt->close();
	//insert into user2
	$add_event7 = $mysqli->prepare("INSERT into events (user, title, `date`, time, catagory, month) values (?, ?, ?, ?, ?, ?)");
	if(!$add_event7){
		printf("Query Failed: %s\n", $mysqli->error);
		exit;
	}
	$add_event7->bind_param('ssssss', $shared_person, $title7, $date7, $time7, $catagory7, $month7);
	$add_event7->execute();
	//make JSON
	echo json_encode(array(
			"success" => true,
			"SharedP" => $shared_person,
			"SharedE" => $shared_event,

			)
		);
		
		exit;
	$add_event7->close();

}

?>