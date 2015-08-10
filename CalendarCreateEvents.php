<?php
//HTTP only
ini_set("session.cookie_httponly", 1);
require 'database1.php';
if(true){
	$username = $mysqli->real_escape_string($_POST['username']);
	$title = $mysqli->real_escape_string($_POST['title_e']);
	$date = $mysqli->real_escape_string($_POST['date_e']);
	$time = $mysqli->real_escape_string($_POST['time_e']);
	$cat = $mysqli->real_escape_string($_POST['cat_e']);
	$month = $mysqli->real_escape_string($_POST['month_e']);
	//Text must be inputted
	if((!preg_match('/^[\w_\-]+$/',$title)) || (!preg_match('/^[\w_\-]+$/',$date)) || (!preg_match('/^[\w_\-]+$/',$time)) || (!preg_match('/^[\w_\-]+$/',$month)) ){
		echo json_encode(array(
			"blank" => true
			));
		exit;
	}
	//Create events
	$add_event = $mysqli->prepare("insert into events (user, title, date, time, catagory, month) values (?, ?, ?, ?, ?, ?)");
	if(!$add_event){
		printf("Query Failed: %s\n", $mysqli->error);
		exit;
	}
	$add_event->bind_param('ssssss', $username, $title, $date, $time, $cat, $month);
	$add_event->execute();
	$add_event->close();
	echo json_encode(array(
		"success" => true
		));
	exit;
}
?>