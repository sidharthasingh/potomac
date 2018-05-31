<?php
	require_once("mysql_connection.php");

	// Start a new session for the given user_id
	function startSession($user_id)
	{
		global $potodb;
		$sess_id;
		if(!is_numeric($user_id))
			return false;
		if($potodb->insertValue("session",array("user_id"=>$user_id)))
		{
			$sess_id=$potodb->last_id();
			$potodb->insertBatch("session_meta",array(
				array("session_id"=>$sess_id,"user_id"=>$user_id,"meta_key"=>"session_status","meta_value"=>"active"),
				array("session_id"=>$sess_id,"user_id"=>$user_id,"meta_key"=>"start_datetime","meta_value"=>date('Y-m-d H:i:s')),
				array("session_id"=>$sess_id,"user_id"=>$user_id,"meta_key"=>"end_datetime","meta_value"=>date('Y-m-d H:i:s',time()+36*3600))
				));
			return $sess_id;
		}
		else
			return false;
	}

	// Stop all the sessions for a given user_id
	function stopAllSessions($uid)
	{
		global $potodb;
		if(is_numeric($uid))
		{
			$sql="update session_meta set meta_value='inactive' where meta_key='session_status' and user_id=$uid and meta_value='active';";
			$dateTime=date('Y-m-d H:i:s');
			$sql1 = "update session_meta set meta_value='$dateTime' where meta_key='end_datetime' and user_id=$sessId;";
			if($potodb->query($sql) && $potodb->query($sql1))
				return true;
			else
				return false;
		}
		else
			return false;
	}

	// Get all the data associated with a session in form of Array[key => value]
	function getSessionData($sessId)
	{
		global $potodb;
		if(is_numeric($sessId))
		{
			$result = $potodb->get_result("select meta_key, meta_value from session_meta where session_id=$sessId;");
			if($result)
			{
				$retArr = array();
				foreach($result as $val)
					$retArr[$val["meta_key"]] = $val["meta_value"];
				return $retArr;
			}
			else
				return false;
		}
		else
			return false;
	}

	// Validate the session if active or inactive
	function validateSession($sessId)
	{
		global $potodb;
		if(!is_numeric($sessId))
			return false;
		$data = getSessionData($sessId);
		if(!$data)
			return false;
		$currentTime = time();
		if($currentTime>strtotime($data["start_datetime"]) && $currentTime<strtotime($data["end_datetime"]) && $data["session_status"]=="active")
			return true;
		else
			return false;
	}

	// Stop the session with the particular sessId.
	function stopSession($sessId)
	{
		global $potodb;
		if(!is_numeric($sessId))
			return false;
		$sql = "update session_meta set meta_value='inactive' where meta_key='session_status' and session_id=$sessId;";
		$dateTime=date('Y-m-d H:i:s');
		$sql1 = "update session_meta set meta_value='$dateTime' where meta_key='end_datetime' and session_id=$sessId;";
		if($potodb->query($sql) && $potodb->query($sql1))
			return true;
		else
			return false;
	}
?>