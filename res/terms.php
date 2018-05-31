<?php
	require_once("mysql_connection.php");

	// Return term_id is term is present
	// Return false otherwise
	function getTermId($term){
		global $potodb;
		$term = strtolower($term);
		$row = $potodb->get_row("select ID from terms where name=\"$term\";");
		if($row === false){
			return false;
		}
		if(!empty($row["ID"])){
			return 0+$row["ID"];
		}
		else{
			return false;
		}
	}

	function getTermName($term_id){
		global $potodb;
		$row = $potodb->get_row("select name from terms where ID=$term_id;");
		if($row === false){
			return false;
		}
		if(!empty($row["name"])){
			return $row["name"];
		}
		else{
			return false;
		}
	}

	// Create a new term and insert in the term table
	// return the term_id if successfull
	// Return the term_id of the term if it is already present
	function insertTerm($term){
		global $potodb;
		$term = strtolower($term);
		$term_id = getTermId($term);
		if($term_id === false){
			// New term insertion
			return $potodb->insertValue("terms",[
				"name" => $term
			]);

		}
		else{
			// Return the already present term_id
			return $term_id;
		}
	}

	// Create a file - term relationship
	// Return relationship_ID
	// Return false if error 
	function createTermIdFileIdRelationship($term_id, $file_id){
		global $potodb;
		if(gettype($term_id) === "integer" && gettype($file_id) === "integer"){
			return $potodb->insertValue("term_relationship",[
				"term_id" => $term_id,
				"file_id" => $file_id
			]);
		}
		else{
			return false;
		}
	}

	function createTermFileIdRelationship($term, $file_id){
		global $potodb;
		if(!empty($term) && gettype($file_id) == "integer"){
			$term_id = getTermId($term);
			if($term_id === false){
				$term_id = insertTerm($term);
			}
			return $potodb->insertValue("term_relationship", [
				"term_id" => $term_id,
				"file_id" => $file_id
			]);
		}
		else{
			return false;
		}
	}

	// return an array of File-ID's taht contain the keywords in the array
	// Return false otherwise
	function searchFromKeywords($keywords){
		global $potodb;
		$l = count($keywords);
		$str = "select file_id, count(file_id) from term_relationship where";
		for($i = 0; $i < $l-1; $i++){
			$keyword = $keywords[$i];
			$term_id = getTermId($keyword);
			$str.=" term_id=$term_id or";
		}
		$keyword = $keywords[$i];
		$term_id = getTermId($keyword);
		$str.=" term_id=$term_id GROUP by file_id;";

		$res = $potodb->get_result($str);
		$file_ids = [];
		if($res){
			foreach($res as $row){
				if($row["count(file_id)"] == $l){
					$file_ids[] = 0+$row["file_id"];
				}
			}
			if(count($file_ids) == 0){
				return false;
			}
			return $file_ids;
		}
		else{
			return $res;
		}
	}

	// var_dump(gettype(1));
	// var_dump(getTermId("hello1"));
?>