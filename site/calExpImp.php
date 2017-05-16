<? /* 
	PAGE FOR DOWNLOADING/EXPORTING THE CALENDAR ics FILE
	NEEDS: SESSION["uuid"]
	UPDATES/imports from DB: With every refresh of the page
*/ ?>
<!DOCTYPE html>
<html>
<head>
  <link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16" />
<title>Import &amp; Export</title>
<link href="menubar.css" rel="stylesheet">
<script src="../site/jquery.min.js"></script>
<script src="../ajax/buttonAjax.js"></script>
<style>
html, body {
	height: 100%;
}
#labform > *{
	overflow-y: scroll;
}
</style>
<script>
$(document).on('submit','#submitKTHlink', function(event) {
	event.preventDefault();
	console.log($(this).serialize());
	$.ajax ({
		type: $(this).attr('method'),
		url: $(this).attr('action'),
		data: $(this).serialize(),
		success: function(data){
			console.log(data);
			document.getElementById('submitKTHlink').outerHTML += data;
		}
	})
});
</script>
</head>
<body>
  <?php
	include '../site/menubar.php';
    if(session_id() == "") {
		session_start();
	}
	if(!isset($_SESSION['uuid'])) {
		include_once "../site/menubar.php";
		include_once "../scripts/createUser.php";
		echo "<h3>forbidden</h3>";
	}
	else {
		include_once '../scripts/DB.php';
		include_once '../scripts/importCal.php';
		include_once '../algorithm/export.php';
		$sessID = $_SESSION["uuid"];
		$db = new DB();
		// Imports the calendar associated with the uuid from the DB
		$cal = $db -> select("SELECT CURRENT FROM calendar WHERE ID = '$sessID'");
		$cal = $cal[0]["CURRENT"];
		// Stores the imported calendar in a calendar file
		export($cal, $sessID);
		// The route to the updated/new calendar file
		$calRoute = "../userStorage/calendar_" . $sessID . ".ics";
		$form = "<h1>Import &amp; Export</h1><a href= '$calRoute' download>EXPORT/DOWNLOAD CALENDAR</a>";
		echo $form;
		$form2 = "<form id='submitKTHlink' action='../scripts/createCal.php' method='POST'>KTHlink:<input type='text' name='KTHlink'/><input type='hidden' name='uuid' value='".$_SESSION['uuid']."'/><input type='submit'/></form>";
		echo $form2;
	}
  ?>
  
</body>
