<?php

	include_once '../scripts/DB.php';
	$db = new DB();
	
	//Get habits from database
	$result = $db -> select("SELECT HABITS FROM data WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
		
	$r = json_decode($result[0]['HABITS'], true);
	$p = array();
	
	//Check if habitname is same as the habit we have to remove. If it is, discard it when pushing to new array
	foreach ($r as $c) {
		if ($c['name'] !== $_POST['remove']) {
			array_push($p, (object)$c);
		}
	}
	
	//Update database with new array of habits
	$db -> query("UPDATE data SET HABITS=" . $db->quote(json_encode($p)) . " WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
	
	//Get habit events from database
	$result = $db -> select("SELECT HABITS FROM calendar WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
		
	$r = json_decode($result[0]['HABITS'], false);
	$p = array();
	
	//Check if habitname is the same as the habit we have to remove. If it is, discard the event when pushing to new array
	foreach($r as $e) {
		if($e->SUMMARY !== $_POST['remove']) { array_push($p, (object)$e); }
	}
	
	//Update database with new array of events
	$db -> query("UPDATE calendar SET HABITS=".$db->quote(json_encode($p))." WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
	
	include 'showHabits.php';
?>