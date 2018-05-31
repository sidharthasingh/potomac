<?php
	require_once("res/mysql_connection.php");
	require_once("res/user.php");
	require_once("res/pass.php");

	function validateTokenSession($user_id, $token){
		global $potodb;

		if(gettype($user_id) != "integer")
			return false;

		$row = $potodb->get_row("select * from login_tokens where user_id=$user_id AND token='$token'");
		if($row == false)
			return false;
		else if(!empty($row["user_id"]) && !empty($row["id"]) && !empty($row["token"])){
			if($row["token"] === $token)
				return 0+$row["id"];
			return false;
		}
		else{
			return false;
		}
	}

	$login_success = false;
	$message = "";

	if(!empty($_COOKIE["ATID"]) && !empty($_COOKIE["PUID"])){
		$token_id = $_COOKIE["ATID"];
		$user_email = $_COOKIE["PUID"];
		$user_id = getUserId(trim($user_email));
		if(gettype($user_id) === "integer" && !empty($user_id)){
			// Do nothing
			// The user is registered.
		}
		else{
			$user_id = "";
			$token_id = "";
		}
		$validSession = validateTokenSession($user_id, $token_id);
		if($validSession === false){
			// Invalide Session
			$login_success = false;
		}
		else if(gettype($validSession) == "integer"){
			$login_success = true;
		}
	}

	if($_POST["email"] && $_POST["password"] && !empty(trim($_POST["email"])) && !empty($_POST["password"])){
		$user_email = trim($_POST["email"]);
		$user_password  = $_POST["password"];
		$user_id = getUserId($user_email);
		if(gettype($user_id) != "integer"){
			$login_success = false;
			$message = "User not found";
		}
		else if(validateUserPass($user_id, $user_password)){
			$temp = true;
			$token = bin2hex(openssl_random_pseudo_bytes(64, $temp));
			$id = $potodb->insertValue("login_tokens", [
				"token" => $token,
				"user_id" => $user_id
			]);
			if($id === false){
				$login_success = false;
				$message = "Unable to Log in";
			}
			else if(gettype($id) == "integer"){
				$login_success = true;
				$message = "Login Successfull";
				setcookie("ATID", $token,time()+ 60 * 60 * 24 * 7,'/', NULL, NULL, TRUE);
				setcookie("PUID", $user_email,time()+ 60 * 60 * 24 * 7,'/', NULL, NULL, TRUE);
			}
		}
		else{
			$login_success = false;
			$message = "Login failed due to incorrect credentials";
		}
	}

	if($login_success === true && !$include_for_search){
		include "search.php";
	}
	else if($login_success === true && $include_for_search){
		// Do nothing
	}
	else{
		include "client/login.php";
	}


?>