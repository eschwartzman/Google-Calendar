<?php
//HTTP only
ini_set("session.cookie_httponly", 1);
require 'database1.php';
if(true){
	$name = $mysqli->real_escape_string($_POST['username']);
	//select posted events
	$stmt = $mysqli->prepare("SELECT  `title`, `date`, `time`, `catagory`, `month` FROM events WHERE user=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('s', $name);
	$stmt->execute();
	$result = $stmt->get_result();
	$result_array= array();
	//XSS protection
	$result_array = array_map('htmlentities', $result_array);
	while($row = $result->fetch_assoc()){
		$result_array[] =$row;
	}
	echo json_encode($result_array);
	exit;
	$stmt->close();
}
?>