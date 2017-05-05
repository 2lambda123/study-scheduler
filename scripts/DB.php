<?php
//Class DB to make connecting and querying db easier, create new like : $db = new DB();
class DB {
	protected static $connection;
	
	//Function connect, connects to database with config's values and returns connection; to save connect instructions in one place. Called by each function so not to have user create connections.
	public function connect() {
		if (!isset($connection)) {
			$config = parse_ini_file('../config.ini'); //Get config file
			$connection = new mysqli($config['host'],$config['username'],$config['password'],$config['dbname']); //Connect with config values
			if ($connection->connect_errno) { //If error, create new db and then try to connect again
				$this -> createDB($config);
				return $this -> connect();
			}
			return $connection;
		}
	}
	
	//If database couldn't be connected, create new one
	private function createDB($config) {
		$mysqli = new mysqli($config['host'],$config['username'],$config['password']); //Connect to config's phpmyadmin, not a selected database
		
		if ($mysqli->connect_errno) { //If couldn't connect, something else is wrong, print error
			printf("Connect failed: %s\n", $mysqli->connect_error);
			exit();
		}

		if ($mysqli->query('CREATE DATABASE STUDYSCHEDULER')) { //Query to create database and echo created
			echo "<br>Database studyscheduler created";
		}

		if ($mysqli->select_db('STUDYSCHEDULER')) { //Select db to create tables inside it, echo selected
			echo "<br>SELECTED DATABASE STUDYSCHEDULER";
		}

		//Sql for table user as per decided by database structure
		$sql = "CREATE TABLE USER (" .
			"ID VARCHAR(36) UNIQUE, " .
			"USERNAME VARCHAR(30) UNIQUE, " .
			"PASSWORD VARCHAR(36), " .
			"SETTINGS TEXT, " .
			"KTHAUTH TEXT, " .
			"FBAUTH TEXT)";
			
		if ($mysqli->query($sql)) { //Create table user, echo table created
			echo "<br>TABLE USER Created";
		}
		
		//Sql for table data as per decided by database structure
		$sql = "CREATE TABLE DATA (" .
			"ID VARCHAR(36) UNIQUE, " .
			"HABITS TEXT, " .
			"COURSES TEXT, " .
			"ROUTINES TEXT, " .
			"KTHlink TEXT)";
			
		if ($mysqli->query($sql)) { //Create table data, echo table created
			echo "<br>TABLE DATA Created";
		}
		
		//Sql for table calendar as per decided by database structure
		$sql = "CREATE TABLE CALENDAR (" .
			"ID VARCHAR(36) UNIQUE, " .
			"STUDY LONGTEXT, " .
			"PERSONAL LONGTEXT, " .
			"HABITS LONGTEXT, " .
			"CURRENT LONGTEXT)";
			
		if ($mysqli->query($sql)) { //Create table calendar, echo table created
			echo "<br>TABLE CALENDAR Created<br>";
		}
	}
	
	//Query database and returns result, used for update, insert etc; $db->query($sql);
	public function query($query) {
		$connection = $this -> connect();
		
		$result = $connection -> query($query);
		
		return $result;
	}
		
	//Select from database, used only for select, which returns result as array of each row; $result = $db->select($sql);
	public function select($query) {
		$rows = array();
		$result = $this -> query($query);
		
		if ($result === false) {
			return false;
		}
		
		while ($row = $result -> fetch_assoc()) {
			$rows[] = $row;
		}
		return $rows;
	}	
	
	//If error occurs, call this function which returns last database error;
	public function error() {
		$connection -> $this -> connect();
		return $connection -> error;
	}
	
	//To prevent sql injection, call this function if a user input is with sql input; $input = $db->quote($input);
	public function quote($value) {
		$connection = $this -> connect();
		return "'" . $connection -> real_escape_string($value) . "'";
	}
}
?>