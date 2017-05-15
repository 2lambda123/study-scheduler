<?php
	if (session_id() == "") session_start();
	include_once '../scripts/DB.php';
	$db = new DB();
	
	//Get habits from database
	$result = null;
	if(isset($_SESSION['uuid'])){
		$result = $db -> select("SELECT HABITS FROM data WHERE ID='".$_SESSION['uuid']."'");
	}
	$r = (isset($result[0]['HABITS'])) ? json_decode($result[0]['HABITS'], true) : null;
	$p = array();

	//Check if habitname is same as the habit we have to remove. If it is, discard it when pushing to new array
	if (is_array($r)) {
		foreach ($r as $c) {
			if ($c['name'] !== $_POST['remove']) {
				array_push($p, (object)$c);
			}
		}
	}

	//Update database with new array of habits

	if(isset($_SESSION['uuid'])){
		$db -> query("UPDATE data SET HABITS=" . $db->quote(json_encode($p)) . " WHERE ID='".$_SESSION['uuid']."'");
	}

	//Get habit events from database

	$result = null;
	if(isset($_SESSION['uuid'])){
		$result = $db -> select("SELECT HABITS FROM calendar WHERE ID='".$_SESSION['uuid']."'");
	}
	$r = (isset($result[0]['HABITS'])) ? json_decode($result[0]['HABITS'], false) : null;
	$p = array();

	//Check if habitname is the same as the habit we have to remove. If it is, discard the event when pushing to new array
	if(is_array($r)){
		foreach($r as $e) {
			if($e->SUMMARY !== $_POST['remove']) { array_push($p, (object)$e); }
		}
	}

	//Update database with new array of events
	if(isset($_SESSION['uuid'])){
		$db -> query("UPDATE calendar SET HABITS=" . $db->quote(json_encode($p)) . " WHERE ID='".$_SESSION['uuid']."'");
	}

	include '../ajax/showHabits.php';
?>
