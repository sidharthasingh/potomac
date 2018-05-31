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
			$all_id = [];

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
		var_dump($file_ids);

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