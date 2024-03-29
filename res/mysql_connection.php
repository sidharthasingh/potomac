<?php
	require_once("config.php");
	class potoDB
	{
		var $conn;
		function __construct()
		{
			$this->conn = new mysqli(DATABASE_HOST,DATABASE_USER,DATABASE_PASS,DATABASE_DB,DATABASE_PORT);
			if(!$this->conn)
				$this->conn=null;
		}

		function insertValue($table, $arr)
		{
			if($this->conn)
			{
				$sql="insert into $table(";
				$keys=array();
				$vals=array();
				foreach($arr as $key => $val)
				{
					$val=str_replace("\\", "\\\\", $val);
					$val=str_replace("'", "\'", $val);
					$keys[]="$key";
					$vals[]="'$val'";
				}
				$sql.=implode(",",$keys).") values(".implode(",",$vals).");";
				if($this->conn->query($sql))
					return mysqli_insert_id($this->conn);
				else
					return false;
			}
			else
				return false;
		}

		// $arr is an array of array
		function insertBatch($table,$arr)
		{
			$count=0;
			foreach($arr as $ar)
				if($this->insertValue($table,$ar))
					$count++;
			if($count==0)
				return false;
			else
				return $count;
		}

		// execute and sql query
		function query($sql)
		{
			if($this->conn)
			{
				$sql=trim($sql);
				if($sql[strlen($sql)-1]!=';')
					$sql.=';';
				$result = $this->conn->query($sql);
				if($result)
					return $result;
				else
					return false;
			}
			else
				return false;
		}

		// Get the last automatically generated ID of the execution.
		function last_id()
		{
			return mysqli_insert_id($this->conn);
		}

		// Get a single row after executing the sql command
		function get_row($sql)
		{
			$sql=trim($sql);
			if($sql[strlen($sql)-1]!=';')
				$sql.=';';
			$result = $this->query($sql);
			if($result->num_rows>0)
				return $result->fetch_assoc();
			return false;
		}

		// Get all the results in form of array of array
		function get_result($sql)
		{
			$sql=trim($sql);
			if($sql[strlen($sql)-1]!=';')
				$sql.=';';
			$result = $this->query($sql);
			if($result->num_rows>0)
			{
				$retArr = array();
				while($row = $result->fetch_assoc())
					$retArr[] = $row;
				return $retArr;
			}
			else
				return false;
		}

		function log($arr,$date="")
		{
			if(!$date)
				$date = date('Y-m-d H:i:s');
			return $this->insertValue("log",array(
					"date_time"=>$date,
					"data"=>json_encode($arr)
				));
		}
	}

	$potodb = new potoDB;
?>