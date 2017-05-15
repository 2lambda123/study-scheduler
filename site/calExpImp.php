<!-- PAGE FOR DOWNLOADING/EXPORTING THE CALENDAR ics FILE -->
<!-- NEEDS: SESSION["uuid"] -->
<!-- UPDATES/imports from DB: With every refresh of the page -->

<!DOCTYPE html>
<html>
<head>
  <link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16" />
<title>Import &amp; Export</title>
<link href="menubar.css" rel="stylesheet">
</head>
<body>
  <?php
    session_start();
    //TESTDATA

    // END OF TESTDATA
    include 'menubar.php';
    include_once '..\scripts\DB.php';
    include_once '..\scripts\importCal.php';
    include_once '..\algorithm\export.php';
    $sessID = $_SESSION["uuid"];
    $db = new DB();
    // Imports the calendar associated with the uuid from the DB
    $cal = $db -> select("SELECT CURRENT FROM calendar WHERE ID = '$sessID'");
  	$cal = $cal[0]["CURRENT"];
    // Stores the imported calendar in a calendar file
    export($cal, $sessID);
    // The route to the updated/new calendar file
    $calRoute = "..\site\calendar_" . $sessID . ".ics";
  ?>
  <h1>Import &amp; Export</h1>
  <a href= "<?php echo $calRoute ?>" download>EXPORT/DOWNLOAD CALENDAR</a>
</body>
