<!DOCTYPE html>
<html>
<head>
<title>Calendar</title>
<link href="menubar.css" rel="stylesheet">
<link href='popupEvent.css' rel='stylesheet'>
<link href="calendar.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="../ajax/buttonAjax.js" defer></script>
<?php include "calendarLoadDays.php" ?>
<?php include "../scripts/popupEvent.php" ?>
</head>


<body>
  <!--  Menu Bar, includes homepage, calendar, personal routines, import/export and logout -->
  <?php include "menubar.php" ?>

  <!-- Calendar Header, includes display of current week and two buttons that on click will view either the next
  or previous weeks -->
  <div id="calHead" value ="0">
    <button class = "weekBtn" id="Prev"> Previous</button>
    <div id="weekHead"> "MIA"</div>
    <button class = "weekBtn" id="Next"> Next</button>
  </div>

  <!-- Calendar table, displays the calendar itself. -->
  <table  id="calendar">
    <!-- Displaying the current weeks calendar --->
    <?php assign_weekHead(getfirstday(0)); ?>
    <?php assign_weekEvent(getfirstday(0)); ?>
	</table>

</body>
</html>
