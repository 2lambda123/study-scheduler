<!DOCTYPE html>
<html>
<head>
<title>Calendar</title>
<link href="menubar.css" rel="stylesheet">
<link href="calendar.css" rel="stylesheet">
<script type="text/javascript" src="calendar_load_week.js" defer></script>
<?php include "calendar_load_days.php" ?>

</head>

<body>
  <ul>
    <li><a href="homepage.php">HOME </a></li>
    <li><a class="active" href="calendar.php">CALENDAR</a></li>
    <li><a href="personal_routines.php">PERSONAL ROUTINES</a></li>
    <li><a href="import_export.php">IMPORT &amp; EXPORT</a></li>
    <li><a href="settings.php">SETTINGS</a></li>
    <li style="float:right"><a href="">LOGOUT</a></li>
  </ul>


  <!--Week Heading-->
  <div id="weekHead"> "MIA" </div>

  <!-- Calendar table -->
	  <table  id="calendar">
		        <tr text-align="center">
      			  <th><?php print_dates("Monday");?></th>
      			  <th><?php print_dates("Tuesday");?></th>
      			  <th><?php print_dates("Wednesday")?></th>
      			  <th><?php print_dates("Thursday");?></th>
      			  <th><?php print_dates("Friday");?></th>
      			  <th><?php print_dates("Saturday");?></th>
      			  <th><?php print_dates("Sunday");?></th>
		        </tr>

            <tr>
              <td class="cells"><div class="box"></div></td>
              <td class="cells"><div class="box"></div></td>
              <td class="cells"><div class="box"></div></td>
              <td class="cells"><div class="box"></div></td>
              <td class="cells"><div class="box"></div></td>
              <td class="cells"><div class="box"></div></td>
              <td class="cells"><div class="box"></div></td>
            </tr>
	  </table>
</body>
</html>
