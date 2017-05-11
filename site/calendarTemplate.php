<body>
  <!--  Menu Bar, includes homepage, calendar, personal routines, import/export and logout -->
  <?php include "../site/menubar.php" ?>
  <?php include "../site/calendarLoadDays.php" ?>
  <?php include_once "../scripts/popupEvent.php" ?>

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
