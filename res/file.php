<?php
	// For pdf files right now
	require_once("mysql_connection.php");

	function insertNewFile($file_name, $file_location){
		global $potodb;
		if(!empty($file_name) && !empty($file_location)){
			return $potodb->insertValue("file", [
				"file_name" => $file_name,
				"file_location" => $file_location
			]);
		}
		else{
			return false;
		}
	}

	function getFileMeta($file_id, $meta_key = false){
		global $potodb;
		if($meta_key === false){
			$row = $potodb->get_result("select meta_key, meta_value from file_meta where file_id=$file_id;");
			if(is_array($row)){
				return $row;
			}
			return false;
		}
		else{
			$row = $potodb->get_row("select * from file_meta where meta_key='$meta_key' AND file_id=$file_id;");
			if(is_array($row)){
				return $row;
			}
			return false;
		}
	}

	function setFileMeta($file_id, $meta_key, $meta_value){
		global $potodb;
		if(gettype($file_id) == "integer" && !empty($meta_key) && !empty($meta_value)){
			$file_meta = getFileMeta($file_id, $meta_key);
			if($file_meta === false){
				// Meta for this file_id and meta_key does not exists.
				return $potodb -> insertValue("file_meta",[
					"file_id" => $file_id,
					"meta_key" => strtoupper($meta_key),
					"meta_value" => strtoupper($meta_value)
				]);
			}
			else{
				// Meta already exists for this file_id
				$meta_id = $file_meta["ID"];
				if($potodb->query("update file_meta set meta_value=\"$meta_value\" where ID=$meta_id;")){
					return $meta_id;
				}
				else{
					return false;
				}
			}
		}
		else{
			return false;
		}
	}

	function getFileName($file_id){
		global $potodb;
		if(gettype($file_id) != "integer"){
			return false;
		}
		$row = $potodb->get_row("select file_name from file where ID=$file_id");
		if($row == false){
			return false;
		}
		else{
			return $row["file_name"];
		}
	}

	function getFileLocation($file_id){
		global $potodb;
		if(gettype($file_id) != "integer"){
			return false;
		}
		$row = $potodb->get_row("select file_location from file where ID=$file_id");
		if($row == false){
			return false;
		}
		else{
			return $row["file_location"];
		}
	}

	function getFullFilePath($file_id){
		global $potodb;
		if(gettype($file_id) != "integer"){
			return false;
		}
		$file_name = getFileName($file_id);
		$file_location = getFileLocation($file_id);
		if($file_name !== false && $file_location !== false){
			return $file_location.$file_name;
		}
		else{
			return false;
		}
	}

	function getFileIdFromMeta($meta_key, $meta_value){
		global $potodb;
		$meta_key = strtoupper($meta_key);
		$meta_value = strtoupper($meta_value);
		$res = $potodb->get_result("select file_id from file_meta where meta_key=\"$meta_key\" and meta_value=\"$meta_value\";");
		if($res === false){
			return false;
		}
		if(is_array($res)){
			$arr = [];
			foreach($res as $a){
				$arr[] = 0+$a["file_id"];
			}
			return $arr;
		}
		return false;
	}
?>