<!DOCTYPE html>
<html>
<head>
<title>Calendar</title>
<link href="menubar.css" rel="stylesheet">
<link href='popupEvent.css' rel='stylesheet'>
<link href="calendar.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="calendar_load_week.js" defer></script>
<script type="text/javascript" src="ajax.js" defer></script>
<?php include "calendar_load_days.php" ?>
<?php include "popupEvent.php" ?>
</head>


<body>
  <!--  Menu Bar, includes homepage, calendar, personal routines, import/export and logout -->
  <ul>
    <li><a href="homepage.php">HOME </a></li>
    <li><a class="active" href="calendar.php">CALENDAR</a></li>
    <li><a href="personal_routines.php">PERSONAL ROUTINES</a></li>
    <li><a href="import_export.php">IMPORT &amp; EXPORT</a></li>
    <li><a href="settings.php">SETTINGS</a></li>
    <li style="float:right"><a href="">LOGOUT</a></li>
  </ul>

  <!-- Calendar Header, includes display of current week and two buttons that on click will view either the next
  or previous weeks -->
  <div id="CalHead">
    <input type='hidden' id='whichweek' value='0'/>
    <button class = "prev" id="Prev"> Previous</button>
    <div id="weekHead"> "MIA"</div>
    <button class = "prev" id="Next"> Next</button>
  </div>

  <!-- Calendar table, displays the calendar itself. -->
  	  <table  id="calendar">
                   <?php assign_weekHead(getfirstday(0)); ?>
                  <?php assign_weekEvent(getfirstday(0)); ?>
	  </table>

</body>
</html>
