<?php
include '../scripts/DB.php';
if (session_id() == "") session_start();
//Get current calendar from database
$db = new DB();
$uuid = $_SESSION['uuid'];
$result = $db -> select("SELECT STUDY FROM calendar WHERE ID='" . $uuid. "'");
$e = json_decode($result[0]['STUDY'], false);
$count = count($e);

//Find all events with name "Laboration" and checks if that event's dtstart exists in $_POST, if it doesn't, remove it from array
for ($i = 0; $i < $count; $i++) {
	if (strstr($e[$i]->SUMMARY, "Laboration")) {
		if (!in_array($e[$i]->DTSTART, $_POST['lab'])) {
			unset($e[$i]);
			$e = array_values($e);
			$i--;
			$count = count($e);
		}
	}
}
//Update database with new events
$db -> query("UPDATE calendar SET STUDY=" . $db->quote(json_encode($e)) . " WHERE ID = '$_SESSION[uuid]'");
?>
