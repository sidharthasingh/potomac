<?php
	require_once("res/mysql_connection.php");

	function logout($user_id, $token){
		global $potodb;
		if(!empty($user_id) && !empty($token) && gettype($user_id) == "integer"){
			$potodb->query("delete from login_tokens where user_id=$user_id AND token='$token'");
			return true;
		}
		return false;
	}

	if(!empty($_COOKIE["ATID"]) && !empty($_COOKIE["PUID"])){
		$token_id = $_COOKIE["ATID"];
		$user_email = $_COOKIE["PUID"];
		$user_id = getUserId(trim($user_email));
		logout($user_id, $token_id);
	}

	require_once("login.php");
?>