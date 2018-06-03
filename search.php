<?php
	$include_for_search = true;
	require_once "login.php";
	
	require_once("res/terms.php");
	require_once("res/file.php");

	if(!empty($_POST["keywords"]) || !empty($_POST["no_of_details"]) || !empty($_POST["selection_json"]))
	{
		// Keyword search
		$keywords = $_POST["keywords"];
		$keywords = replacePuncWithSpaces($keywords);
		$keywords = explode(" ", $keywords);
		$arr = [];
		foreach($keywords as $keyword){
			$keyword = trim($keyword);
			if(!empty($keyword)){
				$arr[] = $keyword;
			}
		}
		$keywords = $arr;
		// var_dump($keywords);

		// Meta search
		$all_id = [];
		if(!empty($_POST["no_of_details"]) && !empty($_POST["selection_json"])){
			$meta = json_decode($_POST["selection_json"]);
			$arr = [];
			$total = $_POST["no_of_details"];
			foreach($meta as $met){
				$met->value = trim($met->value);
				$met->selection = trim($met->selection);
				$met->selection_value = trim($met->selection_value);
				if($met->selection_value == "--OTHER--"){
					$met->selection_value = $met->value;
				}
				if(empty($met->selection_value)){
					$total--;
					continue;
				}
				$arr[] = $met;
			}
			$meta = $arr;

			$temp = 1;
			foreach($meta as $met){
				$ids = getFileIdFromMeta($met->selection, $met->selection_value);
				if($temp == 1 )
					$all_id = $ids;
				else
					$all_id = array_intersect($all_id, $ids);
				$temp = 0;
			}
			$all_id = array_unique($all_id);
			var_dump($all_id);
		}

		$file_ids = searchFromKeywords($keywords);
		$all_id = array_merge($all_id, $file_ids);
		$all_id = array_unique($all_id);

		$search_results = [];
		foreach($all_id as $id){
			$obj = [];
			$obj["file_name"] = getFileName($id);
			$obj["file_location"] = getFullFilePath($id);
			$res = getFileMeta($id,"date_added");
			if($res && $res["meta_value"])
				$date_added = $res["meta_value"];
			else
				$date_added = "-";

			$res = getFileMeta($id,"file_type");
			if($res && $res["meta_value"])
				$file_type = $res["meta_value"];
			else
				$file_type = "-";

			$obj["date_added"] = $date_added;
			$obj["file_type"] = $file_type;
			$search_results[] = $obj;
		}

		// var_dump($search_results);

		require "client/results.php";
		exit;
	
		if($_GET["q"] && !empty($_GET["q"])){
			// A GET request for search
			$search_query = $_GET["q"];
			$keywords = explode(" ", $search_query);
			var_dump($keywords);
			$file_ids = searchFromKeywords($keywords);
			// $file_names = [];
			// foreach($file_ids as $file_id){
			// 	$file_names[] = getFileName($file_id);
			// }
			var_dump($file_ids);
		}
	}
	else{
		require "client/search.html";
	}
?>