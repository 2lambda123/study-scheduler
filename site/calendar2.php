<head>
<link href="menubar.css" rel="stylesheet">
</head>
<?php
session_start();
if(!isset($_SESSION['uuid'])) {
	include_once "../site/menubar.php";
	echo "<h3>forbidden</h3>";
	include_once "../scripts/loginform.php";
}
else {
	include_once "../site/calendar.php"; 
	echo "allowed";
}
?>