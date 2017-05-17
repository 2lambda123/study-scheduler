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
<link href="calImpExp.css" rel="stylesheet">
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
$(document).on('submit','#KTHlink', function(event) {
	event.preventDefault();
	console.log($(this).serialize());
	$.ajax ({
		type: $(this).attr('method'),
		url: $(this).attr('action'),
		data: $(this).serialize(),
		success: function(data){
			//console.log(data);
			document.getElementById('submitKTHlink').outerHTML += data;
		}
	})
});
</script>

</head>
<body>
  <?php
	include_once '../site/menubar.php';
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
    if($cal !== NULL){
      export($cal, $sessID);
    }
		// The route to the updated/new calendar file
		$calRoute = "../userStorage/calendar_" . $sessID . ".ics";

		$uri = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$uri = explode('/', $uri);
		$wholeURL = "http://";
		foreach ($uri as $u) {
			if ($u == "scripts" || $u == "site" || $u == "ajax" || $u == "algorithm") {
				break;
			}
			$wholeURL .= $u . "/";
		}
/*
		
		$form = "<h1>Import &amp; Export</h1><div id='downloadCal'><h2>Download schedule</h2><h3>This link works just like your KTH link. You can use it to sync with your phone or Google Calendar.</h3><input id='downloadURL' type='textinputBox' class='inpuBox' onclick='this.select()' readonly='' value='".$wholeURL."ajax/calExport.php?cal=$sessID'></div><br>";
		echo $form;
		$form2 = "<div id='submitKTHlink'><h2>Import calendar</h2><h3>Insert the link to your <a href='https://www.kth.se/social/home/calendar/settings/'>KTH schedule</a> here to import it</h3><form id='KTHlink' action='../scripts/createCal.php' method='POST'>KTH link:<input type='text' class='inputBox' name='KTHlink'/><input type='hidden' name='uuid' value='".$_SESSION['uuid']."'/>
			<input type='submit' class='logBtn' value='Submit'/></form><div>";
*/
		$form = "<h1>Import &amp; Export</h1><div id='downloadCal'><h3>This link works just like your KTH link. You can use it to sync with your phone or Google Calendar.</h3><input id='downloadURL' type='text' onclick='this.select()' readonly='' value='".$wholeURL."ajax/calExport.php?cal=$sessID'></div><br>";
		echo $form;
		$form2 = "<div id='submitKTHlink'><h3>Insert the link to your <a target=\"_blank\" href='https://www.kth.se/social/home/calendar/settings/'>KTH schedule</a> here to import it</h3><form id='KTHlink' action='../scripts/createCal.php' method='POST'>KTH link:<input type='text' name='KTHlink'/><input type='hidden' name='uuid' value='".$_SESSION['uuid']."'/><input type='submit' value='Submit'/></form><div>";
		echo $form2;
	}
  ?>

</body>
