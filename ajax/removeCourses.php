<?php
	if (session_id() == "") session_start();
	include_once '../scripts/DB.php';
	$db = new DB();
	
	//Get courses from database
	$result = $db -> select("SELECT COURSES FROM data WHERE ID='$_SESSION[uuid]'");
		
	$r = json_decode($result[0]['COURSES'], true);
	$p = array();
	
	//Check if coursecode is same as the course we have to remove. If it is, discard it when pushing to new array
	foreach ($r as $c) {
		if ($c['coursecode'] !== $_POST['remove']) {
			array_push($p, (object)$c);
		}
	}
	
	//Update database with new array of courses
	$db -> query("UPDATE data SET COURSES=" . $db->quote(json_encode($p)) . " WHERE ID='$_SESSION[uuid]'");
	
	include '../ajax/showCourses.php';
?>