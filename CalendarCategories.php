<?php
//HTTP only
ini_set("session.cookie_httponly", 1);
require 'database1.php';
if(true){
	$name = $mysqli->real_escape_string($_POST['username']);
	$catagory = $mysqli->real_escape_string($_POST['catagory']);

 	 	if((!preg_match('/^[\w_\-]+$/',$catagory))){
		echo json_encode(array(
			"blank" => true
			));
		exit;
	}
	
	//select specified catagory
	$stmt = $mysqli->prepare("SELECT  `title`, `date`, `time` , `catagory`, `month` FROM events WHERE user=? and catagory=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('ss', $name, $catagory);
	$stmt->execute();
	$result = $stmt->get_result();
	$result_array= array();
	//XSS attack
	$result_array = array_map('htmlentities', $result_array);
	while($row = $result->fetch_assoc()){
		$result_array[] =$row;
	}
	echo json_encode($result_array);
	exit;
	$stmt->close();
}
?>