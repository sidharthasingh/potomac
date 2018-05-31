<?php

	$req = $_SERVER["REQUEST_URI"];
	
	$req_vars = explode("/", $req);
	
	// Three should enough. Can create more if needed.
	$first = $req_vars[1];
	// $second = $req_vars[2];
	// $third = $req_vars[3];

	if($first == "upload")
	{
		include "upload.php";
		exit;
	}

	if($first == "search"){
		include "search.php";
		exit;
	}

	if($first == "login"){
		include "login.php";
		exit;
	}

	if($first == "logout"){
		include "logout.php";
		exit;
	}

	if($first == ""){
		include "client/upload.html";
		exit;
	}

?>