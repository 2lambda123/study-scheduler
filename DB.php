<?php
class db {
	private $mysqli;

	function connect() {
		$this->mysqli = new mysqli('localhost', 'root', '', "studyscheduler");
		
		if ($this->mysqli->connect_errno) {
			echo "<br><h1>Failed to connect to MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error . "</h1><br>";
		}
	}
	
	function runQ($sql) {
		$stmt = $this->mysqli->prepare($sql);
		$stmt->execute();	
	}
	
	function getQ($sql) {
		$stmt = $this->mysqli->prepare($sql);
		$stmt->execute();
		return $stmt;
	}
}
?>