<!DOCTYPE html>
<html>
<head>
<title>Calendar</title>
<link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16" />
<link href="menubar.css" rel="stylesheet">
<link href='../scripts/popupEvent.css' rel='stylesheet'>
<link href="calendar.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="../ajax/buttonAjax.js" defer></script>
</head>
<?php
if(session_id() == "") session_start();
if(!isset($_SESSION['uuid'])) {
	include_once "../site/menubar.php";
	echo "<h3>forbidden</h3>";
	include_once "../scripts/loginform.php";
}
else {
	include_once "../site/calendarTemplate.php";
}
include_once '../scripts/createUser.php';
?>
</html>
