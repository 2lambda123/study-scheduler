<?php
if(isset($_GET['cal'])) {
	include_once '../scripts/DB.php';
	include_once '../scripts/importCal.php';
	include_once '../algorithm/export.php';
	$sessID = $_GET["cal"];
	$db = new DB();
	// Imports the calendar associated with the uuid from the DB
	$cal = $db -> select("SELECT CURRENT FROM calendar WHERE ID = '$sessID'");
	$cal = $cal[0]["CURRENT"];
	// Stores the imported calendar in a calendar file
	export($cal, $sessID);
	// The route to the updated/new calendar file
	$calRoute = "../userStorage/calendar_" . $sessID . ".ics";
	header('Location: '.$calRoute);
}

?>