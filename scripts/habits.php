<?php
include_once 'DB.php';

class event {
	public $SUMMARY = NULL;
	public $DTSTART = NULL;
	public $DTEND = NULL;
	public $UID = NULL;
	public $DESCRIPTION = NULL;
	public $LOCATION = NULL;
	public $AVAILABLE = NULL;
}

$h = $_POST;
$events;
$x = $h['duration'];
$db = new DB();
$result = $db -> select("SELECT HABITS FROM data WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
		
$r = json_decode($result[0]['HABITS'], true);
$p = array();
	
if (is_array($r)) {
	foreach ($r as $c) {
		if ($_POST['name'] == $c['name']) {
			die('You cant add the same habit twice.');
		}
	}
}
	
if ($r !== "") {
	if (is_array($r)) {
		array_push($r, (object)$_POST);
		$p = $r;
	} else {
		array_push($p, (object)$_POST);
	}
}
$db -> query("UPDATE data SET HABITS=".$db->quote(json_encode($p))." WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");

$wD[] = array();
if (isset($h['Monday'])) {
		array_push($wD, 'Monday');
	}
	if (isset($h['Tuesday'])) {
		array_push($wD, 'Tuesday');
	}
	if (isset($h['Wednesday'])) {
		array_push($wD, 'Wednesday');
	}
	if (isset($h['Thursday'])) {
		array_push($wD, 'Thursday');
	}
	if (isset($h['Friday'])) {
		array_push($wD, 'Friday');
	}
	if (isset($h['Saturday'])) {
		array_push($wD, 'Saturday');
	}
	if (isset($h['Sunday'])) {
		array_push($wD, 'Sunday');
}

if ($h['repetition'] == "Daily") {
	$d = date('Ymd');
	for ($i = 0; $i < $x; $i++) {
		$events[] = new event();
		$events[$i]->SUMMARY = $h['name'];
		$events[$i]->DESCRIPTION = $h['travel'];
		$events[$i]->LOCATION = $h['location'];
		$events[$i]->DTSTART = $d . "T" . str_replace(":", "", $h['dtstart']) . "Z";
		$events[$i]->DTEND = $d . "T" . str_replace(":", "", $h['dtend']) . "Z";
		$events[$i]->UID = $d . $h['dtstart'];
		$events[$i]->AVAILABLE = FALSE;
		$d = date('Ymd', strtotime($d . "+1 day"));
	}
} else if ($h['repetition'] == "Weekly") {
	$rep = (count($wD)-1)*$x;
	$d = date('Ymd');
	for ($i = 0; $i < $rep;) {
		if (in_array(date('l', strtotime($d)), $wD)) {
			$events[] = new event();
			$events[$i]->SUMMARY = $h['name'];
			$events[$i]->DESCRIPTION = $h['travel'];
			$events[$i]->LOCATION = $h['location'];
			$events[$i]->DTSTART = $d . "T" . str_replace(":", "", $h['dtstart']) . "Z";
			$events[$i]->DTEND = $d . "T" . str_replace(":", "", $h['dtend']) . "Z";
			$events[$i]->UID = $d . $h['dtstart'];
			$events[$i]->AVAILABLE = FALSE;
			$i++;
		}
		$d = date('Ymd', strtotime($d . "+1 day"));
	}
}
$result = $db -> select("SELECT HABITS FROM calendar WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
		
$r = json_decode($result[0]['HABITS'], true);
$p = array();
include_once 'modify.php';
//ta resultatet, lägg in events i array, och om det finns events i resultatet, lägg in de också, som sen skickas till databasen.
if(!empty($r)){
		$r = json_encode($r);
		foreach($events as $event){
			$r = modify($r, json_encode($event));
		}
		$p = json_decode($r);
}
else{
	$p = $events;
}
$db -> query("UPDATE calendar SET HABITS=".$db->quote(json_encode($p))." WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
/*
$result = $db -> select("SELECT CURRENT FROM calendar WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
		
$r = json_decode($result[0]['CURRENT'], true);
$p = array();
include_once 'modify.php';
//ta resultatet, lägg in events i array, och om det finns events i resultatet, lägg in de också, som sen skickas till databasen.
if(!empty($r)){
		$r = json_encode($r);
		foreach($events as $event){
			$r = modify($r, json_encode($event));
		}
		$p = json_decode($r);
}
else{
	$p = $events;
}

$db -> query("UPDATE calendar SET CURRENT=".$db->quote(json_encode($p))." WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
*/
include 'showHabits.php';
?>