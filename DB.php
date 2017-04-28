<?php
class DB {
	protected static $connection;
	
	private function drop() {
		$config = parse_ini_file('../config.ini');
		$mysqli = new mysqli($config['host'],$config['username'],$config['password']);
		$mysqli -> query('DROP DATABASE studyscheduler');
		echo "Dropped database";
	}
	
	public function connect() {
		if (!isset($connection)) {
			$config = parse_ini_file('../config.ini');
			$connection = new mysqli($config['host'],$config['username'],$config['password'],$config['dbname']);
			if ($connection->connect_errno) {
				$this -> createDB($config);
				return $this -> connect();
			}
			return $connection;
		}
	}
	
	private function createDB($config) {
		$mysqli = new mysqli($config['host'],$config['username'],$config['password']);
		
		if ($mysqli->connect_errno) {
			printf("Connect failed: %s\n", $mysqli->connect_error);
			exit();
		}

		if ($mysqli->query('CREATE DATABASE STUDYSCHEDULER')) {
			echo "Database created";
		}

		if ($mysqli->select_db('STUDYSCHEDULER')) {
			echo "SELECTED DATABASE STUDYSCHEDULER";
		}

		$sql = "CREATE TABLE USER (" .
			"ID VARCHAR(36) UNIQUE, " .
			"USERNAME VARCHAR(30) UNIQUE, " .
			"PASSWORD VARCHAR(36), " .
			"SETTINGS TEXT, " .
			"KTHAUTH TEXT, " .
			"FBAUTH TEXT)";
			
		if ($mysqli->query($sql)) {
			echo "TABLE USER Created";
		}

		$sql = "CREATE TABLE DATA (" .
			"ID VARCHAR(36) UNIQUE, " .
			"HABITS TEXT, " .
			"COURSES TEXT, " .
			"ROUTINES TEXT)";
			
		if ($mysqli->query($sql)) {
			echo "TABLE DATA Created";
		}

		$sql = "CREATE TABLE CALENDAR (" .
			"ID VARCHAR(36) UNIQUE, " .
			"STUDY LONGTEXT, " .
			"PERSONAL LONGTEXT, " .
			"HABITS LONGTEXT, " .
			"CURRENT LONGTEXT)";
			
		if ($mysqli->query($sql)) {
			echo "TABLE CALENDAR Created";
		}

		$mysqli->close();
	}
	
	public function query($query) {
		$connection = $this -> connect();
		
		$result = $connection -> query($query);
		
		return $result;
	}
	
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
	
	public function error() {
		$connection -> $this -> connect();
		return $connection -> error;
	}
	
	public function quote($value) {
		$connection = $this -> connect();
		return "'" . $connection -> real_escape_string($value) . "'";
	}
}
?>