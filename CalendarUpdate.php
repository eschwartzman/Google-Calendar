<?php
//HTTP only
ini_set("session.cookie_httponly", 1);
session_start();
require 'database1.php';
if(true){
	//Check for CSRF
	if($_SESSION['token'] == substr(md5(rand()), 0, 10))
	{
 	echo "error";
	die("Request forgery detected");
	}
	$name = $mysqli->real_escape_string($_POST['username']);
	$old_title = $mysqli->real_escape_string($_POST['title_of_edit_event']);
	$new_title = $mysqli->real_escape_string($_POST['title_of_edited_event']);
	$new_date = $mysqli->real_escape_string($_POST['date_of_edited_event']);
	$new_time = $mysqli->real_escape_string($_POST['time_of_edited_event']);
	$new_catagory = $mysqli->real_escape_string($_POST['catagory_name2']);
	$new_month = $mysqli->real_escape_string($_POST['month_of_edited_event']);
 	if((!preg_match('/^[\w_\-]+$/',$old_title)) || (!preg_match('/^[\w_\-]+$/',$new_title)) || (!preg_match('/^[\w_\-]+$/',$new_date)) || (!preg_match('/^[\w_\-]+$/',$new_time)) || (!preg_match('/^[\w_\-]+$/',$new_catagory)) || (!preg_match('/^[\w_\-]+$/',$new_month)) ){
		echo json_encode(array(
			"blank" => true
			));
		exit;
	}
	//update posted events
	$stmt = $mysqli->prepare("UPDATE events set title=?, `date`=?, time=?, catagory=?, month=? where user='$name' and title='$old_title'");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('sssss', $new_title, $new_date, $new_time, $new_catagory,$new_month);
	$stmt->execute();
		echo json_encode(array(
			"success" => true,
			"Old_Title" => $old_title,
			"New_Title" => $new_title,		
			)
		);		
		exit;	
	$stmt->close();
}
?>