<?php
	require_once("functions.php");

	$arr = [
		"delete from file;",
		"delete from file_meta;",
		"delete from terms;",
		"delete from term_relationship;"
	];

	foreach($arr as $a){
		echo "\n<br>\n";
		echo $a." : ";
		var_dump($potodb->query($a));
	}
?>