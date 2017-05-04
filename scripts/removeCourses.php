<?php

	include_once 'DB.php';
	$db = new DB();
	$result = $db -> select("SELECT COURSES FROM data WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
		
	$r = json_decode($result[0]['COURSES'], true);
	$p = array();
	
	foreach ($r as $c) {
		if ($c['coursecode'] !== $_POST['remove']) {
			array_push($p, (object)$c);
		}
	}
	
	$db -> query("UPDATE data SET COURSES=" . $db->quote(json_encode($p)) . " WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
	
	include 'showCourses.php';
?>