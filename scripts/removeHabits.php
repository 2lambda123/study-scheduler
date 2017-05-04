<?php

	include_once 'DB.php';
	$db = new DB();
	$result = $db -> select("SELECT HABITS FROM data WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
		
	$r = json_decode($result[0]['HABITS'], true);
	$p = array();
	
	foreach ($r as $c) {
		if ($c['name'] !== $_POST['remove']) {
			array_push($p, (object)$c);
		}
	}
	
	$db -> query("UPDATE data SET HABITS=" . $db->quote(json_encode($p)) . " WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
	
	$result = $db -> select("SELECT HABITS FROM calendar WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
		
	$r = json_decode($result[0]['HABITS'], false);
	$p = array();
	
	foreach($r as $e) {
		if($e->SUMMARY !== $_POST['remove']) { array_push($p, (object)$e); }
	}
	
	$db -> query("UPDATE calendar SET HABITS=".$db->quote(json_encode($p))." WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
	
	include 'showHabits.php';
?>