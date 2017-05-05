<?php
include '../scripts/DB.php';

//Get current calendar from database
$db = new DB();
$result = $db -> select("SELECT CURRENT FROM calendar WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
$e = json_decode($result[0]['CURRENT']);
$count = count($e);

//Find all events with name "Laboration" and checks if that event's dtstart exists in $_POST, if it doesn't, remove it from array
for ($i = 0; $i < $count; $i++) {
	if (!in_array($e[$i]->DTSTART, $_POST['lab']) && strstr($e[$i]->SUMMARY, 'Laboration')) {
		unset($e[$i]);
		$e = array_values($e);
		$i--;
		$count = count($e);
	}
}
//Update database with new events
$db -> query("UPDATE calendar SET CURRENT='" . json_encode($e) . "' WHERE ID = 'c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
?>
