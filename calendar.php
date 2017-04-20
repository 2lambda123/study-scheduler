<!DOCTYPE html>
<html>
<head>
<title>Calendar</title>
<link href="menubar.css" rel="stylesheet">
<link href="calendar.css" rel="stylesheet">
<script type="text/javascript" src="calendar_load_week.js" defer></script>
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

  <div id="weekHead"> "MIA" </div>
<div id="week">
    <ul class="weekdays">
      <li>Monday<div id="monbox"></div></li>
      <li>Tuesday<div id="tuebox"> </div></li>
      <li>Wednesday<div id="wedbox"> </div></li>
      <li>Thursday<div id="thubox"> </div></li>
      <li>Friday<div id="fribox"> </div></li>
      <li>Saturday<div id="satbox"> </div></li>
      <li>Sunday<div id="sunbox"> </div></li>
    </ul>
</div>
</body>
</html>
