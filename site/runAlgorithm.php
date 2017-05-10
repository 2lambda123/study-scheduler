<?php
	if (session_id() == "") session_start();
  include_once '../scripts/DB.php';
  include_once '../algorithm/find.php';
  include_once '../algorithm/analyze.php';
  include_once '../algorithm/distribute.php';
  include_once '../scripts/importCal.php';

  echo "<h3>Super good algorithm is running</h3>";
  $db = new DB();
  $calendarLink = $db -> select("SELECT STUDY FROM calendar WHERE ID ='$_SESSION[uuid]'");
  $calendar = $calendarLink[0]["STUDY"];

  //$calendar = downloadFile($calendarLink);

  $calendarPersonal = $db -> select("SELECT PERSONAL FROM calendar WHERE ID ='$_SESSION[uuid]'");
  $calendarPersonal = $calendarPersonal[0]["PERSONAL"];

  $calendarPersonal = json_decode($calendarPersonal, true);

  $calendarHabits = $db -> select("SELECT HABITS FROM calendar WHERE ID ='$_SESSION[uuid]'");
  $calendarHabits = $calendarHabits[0]["HABITS"];

  $calendarHabits = json_decode($calendarHabits, true);

  if(isset($calendarPersonal)) {
    foreach($calendarPersonal as $key)
      $calendar = modify($calendar, json_encode($key));
  }

  if(isset($calendarHabits)) {
    foreach ($calendarHabits as $key)
      $calendar = modify($calendar, json_encode($key));
    }

  $calendarRoutines = $db -> select("SELECT ROUTINES FROM data WHERE ID ='$_SESSION[uuid]'");
  $calendarRoutines = $calendarRoutines[0]["ROUTINES"];

  $calendarCourses = $db -> select("SELECT COURSES FROM data WHERE ID ='$_SESSION[uuid]'");
  $calendarCourses = $calendarCourses[0]["COURSES"];

  $calendar = free_time_with_events($calendar);
  $calendar = analyze($calendar, $calendarRoutines);
  
  $calendar = distribute($calendar, $calendarCourses, $calendarRoutines);

  $db -> query("UPDATE calendar SET CURRENT=".$db->quote($calendar) ." WHERE ID='$_SESSION[uuid]'");

?>
