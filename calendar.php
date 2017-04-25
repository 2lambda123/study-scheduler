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
              <td class="box"><div class="days"><?php echo position(0);?></div></td>
              <td class="box"><div class="days"><?php echo position(1);?></div></td>
              <td class="box"><div class="days"><?php echo position(2);?></div></td>
              <td class="box"><div class="days"><?php echo position(3);?></div></td>
              <td class="box"><div class="days"><?php echo position(4);?></div></td>
              <td class="box"><div class="days"><?php echo position(5);?></div></td>
              <td class="box"><div class="days"><?php echo position(6);?></div></td>
            </tr>
	  </table>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>


</body>
</html>
