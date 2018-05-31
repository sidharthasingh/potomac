<?php

	require_once "res/config.php";
	require_once "res/error.php";
	require_once "res/mysql_connection.php";
	require_once "res/pass.php";
	require_once "res/session.php";
	require_once "res/user.php";
	require_once "res/terms.php";
	require_once "res/file.php";

	// Return true if the character is a punctuation mark
	function isPunc($char)
	{
		$chars = "~`!#$%^&*()_+={[}]|\:;\"'<,>.?/·�•��\n\t";
		$l = strlen($chars);
		for($i = 0; $i < $l; $i++)
		{
			$a = $chars[$i];
			if($a === $char)
			{
				return true;
			}
		}
		if(ord($char)>255 || ord($char)<0)
			return false;
		return false;
	}

	// Return the text without the punctuation marks
	function replacePuncWithSpaces($text)
	{
		$ret = "";
		$l = strlen($text);
		for($i = 0; $i < $l; $i++)
		{
			$a = $text[$i];
			if(isPunc($a))
			{
				$ret.=" ";
			}
			else
			{
				$ret.=$a;
			}
		}
		return $ret;
	}

	// Set the response header as JSON
	function setHeaderToJson(){
		header("Content-type:application/json");
	}

?>