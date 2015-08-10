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
	$title = $mysqli->real_escape_string($_POST['delete_title']);
	if((!preg_match('/^[\w_\-]+$/', $title))){
		echo json_encode(array(
			"blank" => true
			));
		exit;
	}
	//delete posted events
	$stmt = $mysqli->prepare("DELETE FROM events WHERE user=? and title=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('ss', $name, $title);
	$stmt->execute();
		echo json_encode(array(
			"success" => true,
			"Title" => $title			
			)
		);	
		exit;
	$stmt->close();
}
?>






