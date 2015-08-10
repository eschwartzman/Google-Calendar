<?php
require 'database1.php';
if(true){
	$username = $mysqli->real_escape_string($_POST['username']);
	$password = $mysqli->real_escape_string($_POST['password']);
	$crypt_pass = crypt($password);
	$nameUsed = False;
	if((!preg_match('/^[\w_\-]+$/',$username)) || (!preg_match('/^[\w_\-]+$/',$password))){
		echo json_encode(array(
			"blank" => true
			));
		exit;
	}
	$stmt = $mysqli->prepare("SELECT COUNT(*), username, password FROM user_information WHERE username=?");
// Bind the parameter
	$stmt->bind_param('s', $username);
	$username = $_POST['username'];
	$stmt->execute();
// Bind the results
	$stmt->bind_result($cnt, $user_id, $pwd_hash);
	$stmt->fetch();
	$pwd_guess = $_POST['password'];
// Compare the submitted password to the actual password hash
	if( $cnt == 1 && crypt($pwd_guess, $pwd_hash)==$pwd_hash){
	// Login succeeded!
		//HTTP only
		ini_set("session.cookie_httponly", 1);
		session_start();
		$_SESSION['username'] = $username;
		$_SESSION['token'] = substr(md5(rand()), 0, 10);
		echo json_encode(array(
			"success" => true
			));
		exit;	
	// Failed
	}else{
		echo json_encode(array(
			"success" => false,
			"message" => "Invalid Login"
			));
		exit;  	
	}
	$stmt->close();
}
?>