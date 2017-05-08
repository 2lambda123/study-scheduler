<?php
  include_once '../scripts/DB.php';
  include_once '../algorithm/find.php';
  include_once '../algorithm/analyze.php';
  include_once '../algorithm/distribute.php';
  include_once '../scripts/importCal.php';

  echo "<h3>Super good algorithm is running</h3>";
  $db = new DB();
  $calendarLink = $db -> select("SELECT KTHlink FROM data WHERE ID ='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
  $calendarLink = $calendarLink[0]["KTHlink"];

  $calendar = downloadFile($calendarLink);

  $calendarPersonal = $db -> select("SELECT PERSONAL FROM calendar WHERE ID ='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
  $calendarPersonal = $calendarPersonal[0]["PERSONAL"];

  $calendarPersonal = json_decode($calendarPersonal, true);

  $calendarHabits = $db -> select("SELECT HABITS FROM calendar WHERE ID ='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
  $calendarHabits = $calendarHabits[0]["HABITS"];

  $calendarHabits = json_decode($calendarHabits, true);

  if(isset($calendarPersonal)) {
    foreach($calendarPersonal as $key)
      $calendar = modify(json_encode($calendar), json_encode($key));
  }

  if(isset($calendarHabits)) {
    foreach ($calendarHabits as $key)
      $calendar = modify($calendar, json_encode($key));
    }

  $calendarRoutines = $db -> select("SELECT ROUTINES FROM data WHERE ID ='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
  $calendarRoutines = $calendarRoutines[0]["ROUTINES"];

  $calendarCourses = $db -> select("SELECT COURSES FROM data WHERE ID ='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
  $calendarCourses = $calendarCourses[0]["COURSES"];

  $calendar = free_time_with_events($calendar);
  $calendar = analyze($calendar, $calendarRoutines);
  $calendar = distribute($calendar, $calendarCourses, $calendarRoutines);

  $db -> query("UPDATE calendar SET CURRENT=".$db->quote($calendar) ." WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");

?>
