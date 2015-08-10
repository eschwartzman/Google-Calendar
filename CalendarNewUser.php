<?php
require 'database1.php';
if(true){
	$username = $mysqli->real_escape_string($_POST['username']);
	$password = $mysqli->real_escape_string($_POST['password']);
	$nameUsed = False;
	//Checks that there is text
	if((!preg_match('/^[\w_\-]+$/',$username)) || (!preg_match('/^[\w_\-]+$/',$password))){
		echo json_encode(array(
			"blank" => true
			));
		exit;
	}
    //check if username already exists
	$stmt = $mysqli->prepare("select username from user_information where username=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
	}
	$stmt->bind_param('s', $username);
	$stmt->execute();		
	$result = $stmt->get_result();
	while($row = $result->fetch_assoc()){
		if (sizeof($row["username"])>0){ 
			$nameUsed= True;
			echo json_encode(array(
				"success" => false,
				"message" => "Username taken"
				));
			exit;  				
		}
	}
  	//allow the name/password to be added to table
	if(!$nameUsed){
		$crypt_pass = crypt($password);
		$add_name = $mysqli->prepare("insert into user_information (username, password)
			values('$username', '$crypt_pass')");
		if(!$add_name){
			printf("Query Failed: %s\n", $mysqli->error);
			exit;
		}
		$add_name->execute();
		$add_name->close();
		//HTTP only
		ini_set("session.cookie_httponly", 1);
		session_start();
		$_SESSION['username'] = $username;
		$_SESSION['token'] = substr(md5(rand()), 0, 10);
		echo json_encode(array(
			"success" => true
			));
		exit;
	}
	$stmt->close();
}
?>