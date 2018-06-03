<?php

	require_once("res/config.php");

	// var_dump($_POST);
	$target_dir = "content/default/";

	$name_of_file = str_replace(" ","_", basename($_FILES["fileToUpload"]["name"]));

	$target_file = $target_dir . $name_of_file;

	$uploadOk = true;

	$file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	
	if(!empty($file_type)){
		$target_dir = "content/$file_type/";
		$target_file = $target_dir . $name_of_file;
	}

	$l = strrpos($name_of_file, $file_type);
	$name_of_file = substr($name_of_file,0, $l-1);

	// Check if file already exists
	if (file_exists($target_file)) {
		$num = 0;
		do{
			$num++;
			$file_name = $target_dir.$name_of_file."_".$num.".".$file_type;
		}
		while(file_exists($file_name));
		$name_of_file = $name_of_file."_".$num.".".$file_type;
		$target_file = $target_dir.$name_of_file;
	}
	else{
		$name_of_file = $name_of_file.".".$file_type;
		$target_file = $target_dir.$name_of_file;
	}

	// echo $name_of_file;
	// echo "<br>";
	// echo $target_dir;
	// echo "<br>";
	// echo $target_file;

	// exit;

	$upload_response = [
		"error" => null,
		"message" => null,
		"file_name" => $name_of_file,
		"file_location" => $target_dir,
		"file_full_path" => $target_file,
		"file_type" => $file_type
	];

	// Check file size
	if ($_FILES["fileToUpload"]["size"] > MAX_FILE_UPLOAD_SIZE) {
		// setHeaderToJson();
		$upload_response["error"] = true;
		$upload_response["message"] = "File size more than the permitted file size, i.e : ".MAX_FILE_UPLOAD_SIZE." BYTES.";
		$uploadOk = false;
	}

	// Allow certain file formats
	if($file_type != "pdf" && $file_type != "docx" && $file_type != "doc") {
		$upload_response["error"] = true;
		$upload_response["message"] = "File not a supported one. Only pdf, doc and docx files allowed.";
		$uploadOk = false;
	}
	// if everything is ok, try to upload file
	if($uploadOk !== false){
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
			$upload_response["error"] = false;
			$upload_response["message"] = "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			$file_id = insertNewFile($name_of_file, $target_dir);
			if($file_id !== false){
				setFileMeta($file_id, "file_id", $file_id);
				setFileMeta($file_id, "file_type", $file_type);
				setFileMeta($file_id, "file_name", $name_of_file);
				setFileMeta($file_id, "file_location", $target_dir);
				setFileMeta($file_id, "file_full_path", $target_file);
				setFileMeta($file_id, "date_added", date("Y-M-D"));
				$upload_response["file_id"] = $file_id;
			}
		} 
		else{
			echo "Sorry, there was an error uploading your file.";
			$upload_response["error"] = true;
			$upload_response["message"] = "Some unindentified error occured while saving your file";
		}
	}

	setHeaderToJson();
	echo json_encode($upload_response);

	$meta_count = 0;

	if($file_id !== false){
		if(!empty($_POST["no_of_details"]) && !empty($_POST["selection_json"])){
			$json_string = $_POST["selection_json"];
			$arr = json_decode($json_string);
			$tempArr = [];
			foreach($arr as $det){
				$det->selection = trim($det->selection);
				$det->selection_value = trim($det->selection_value);
				$det->value = trim($det->value);
				if(!empty($det->selection_value) && !empty($det->selection) && gettype($det->id) == "integer"){
					if($det->selection_value == "--OTHER--" && !empty($det->value))
						$det->selection_value = $det->value;
					else if($det->selection_value == "--OTHER--" && empty($det->value))
						continue;
					$tempArr[] = $det;			
				}
			}

			$arr = $tempArr;
			foreach($arr as $obj){
				$res = setFileMeta($file_id, $obj->selection, $obj->selection_value);
				if($res !== false)
					$meta_count++;
			}
		}

		$text = "";
		if($file_type == "pdf"){
			$parser = new \Smalot\PdfParser\Parser();
			$pdf    = $parser->parseFile($target_file);
			$text = $pdf->getText();
		}
		else if($file_type = "docx" || $file_type == "doc"){
			$response = RD_Text_Extraction::convert_to_text($target_file);
			$text = $response;
		}
		$arr = explode(" ",strtolower(replacePuncWithSpaces($text)));
		
		$emparr = [];
		for($i = 0; $i<count($arr); $i++)
		{
			if(!empty($arr[$i]) && strlen($arr[$i]) >= MIN_KEYWORD_SIZE)
				$emparr[$arr[$i]] = true;
		}
		$arr = array_keys($emparr);
		foreach($arr as $keyword){
			$term_id = insertTerm($keyword);
			createTermIdFileIdRelationship($term_id, $file_id);
		}
	}

?>