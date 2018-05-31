<?php
	require_once("session.php");
	require_once("pass.php");
	
	// Create a new user with the following parameters
	function createNewUser($login,$pass,$email,$fname,$lname,$ustatus)
	{
		global $potodb;
		return $potodb->insertValue("users",array(
			"user_login"=>$login,
			"user_pass"=>md5($pass),
			"user_email"=>$email,
			"user_registered"=>date('Y-m-d H:i:s'),
			"first_name"=>$fname,
			"last_name"=>$lname,
			"user_status"=>$ustatus
			));		
	}

	// Return the meta data associated with an user, returns a value if meta_key is given, else returns and key-value array
	function getUserMeta($uid,$meta_key="")
	{
		global $potodb;
		if(!is_numeric($uid) || strpos("$meta_key",";"))
			return false;
		if($meta_key=="")
		{
			$sql="select meta_key, meta_value from user_meta where user_id=$uid;";
			$result=$potodb->get_result($sql);
			if($result)
			{
				$retArr = array();
				foreach($result as $val)
					$retArr[$val["meta_key"]]=$val["meta_value"];
				return $retArr;
			}
			else
				return false;
		}
		else
		{
			$sql = "select meta_value from user_meta where user_id=$uid and meta_key='$meta_key';";
			$result = $potodb->get_row($sql);
			if($result)
				return $result["meta_value"];
			else
				return false;
		}
	}

	// return data associated with the user from the user table.
	function getUserData($uid,$ch)
	{
		global $potodb;
		if(!is_numeric($uid) || strpos("$ch",";"))
			return false;
		$result = $potodb->get_row("select $ch from users where ID=$uid;");
		if($result)
			return $result[$ch];
		else
			return false;
	}

	// Set the data for the user with user_id = $uid and key and value as the data_title and its value. $key should be an attribute of the table 'user'.
	function setUserData($uid,$key,$val)
	{
		global $potodb;
		if(!is_numeric($uid) || strpos("$key",";") || strpos("$val",";"))
			return false;
		if($key == "user_pass")
			$val=md5($val);
		return $potodb->query("update users set user_pass='$val' where id=$uid;");
	}

	// Set a single meta data for the user key (meta key) => val (meta value)
	function setUserMeta($uid,$key,$val)
	{
		global $potodb;
		$arr = array(
				"user_id"=>$uid,
				"meta_key"=>$key,
				"meta_value"=>$val
			);
		return $potodb->insertValue("user_meta",$arr);
	}

	// Set meta_data for the user. $arr is an Array[Key => value]
	function setUserMetaBulk($uid,$arr)
	{
		global $potodb;
		for($i=0;$i<count($arr);$i++)
			$arr[$i]["user_id"] = $uid;
		return $potodb->insertBatch("user_meta",$arr);
	}

	// Get the complete user as an array from the user_id
	function getUser($uid)
	{
		global $potodb;
		$sql = "select * from users where ID=$uid";
		$user = $potodb->get_row($sql);
		$meta = getUserMeta($uid);
		if($user && $meta)
			return array_merge($user,$meta);
		else
			return false;
	}

	// Return the user_id of the user by taking its email as the input
	function getUserId($email)
	{
		global $potodb;
		if(strpos($email,";"))
			return false;
		$result = $potodb->get_row("select ID from users where user_email='$email';");
		if($result)
			return (int)$result["ID"];
		else
			return false;
	}

?>