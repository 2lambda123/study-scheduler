<!-- PAGE FOR DOWNLOADING/EXPORTING THE CALENDAR ics FILE -->
<!-- NEEDS: SESSION["uuid"] -->
<!-- UPDATES/imports from DB: With every refresh of the page -->

<!DOCTYPE html>
<html>
<title>Import &amp; Export</title>
<link href="menubar.css" rel="stylesheet">
<body>
  <?php
    session_start();
    //TESTDATA

    // END OF TESTDATA
    include 'menubar.php';
    include '..\scripts\DB.php';
    include '..\scripts\importCal.php';
    include '..\algorithm\export.php';
    $sessID = $_SESSION["uuid"];
    $db = new DB();
    // Imports the calendar associated with the uuid from the DB
    $cal = $db -> select("SELECT STUDY FROM calendar WHERE ID = '$sessID'");
  	$cal = $cal[0]["STUDY"];
    // Stores the imported calendar in a calendar file
    export($cal, $sessID);
    // The route to the updated/new calendar file
    $calRoute = "..\site\calendar_" . $sessID . ".ics";
  ?>
  <h1>Import &amp; Export</h1>
  <a href= "<?php echo $calRoute ?>" download>EXPORT/DOWNLOAD CALENDAR</a>
</body>
