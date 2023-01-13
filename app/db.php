<?php

class db
{
	private $db;
	private $conn;
	
	public function __construct($params)
	{
		$this->db = $params;
		$this->conn  = null;
	}
	public function connect()
	{
		try 
		{
			$this->conn = new PDO("mysql:host=".$this->db['host'].";dbname=".$this->db['database']."", $this->db['user'],$this->db['password']); 
			// set the PDO error mode to exception
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		  
		} 
		catch(PDOException $e)
		{
		  echo "Connection failed: " . $e->getMessage();
		  exit;
		}
		return $this->conn;
	}
}