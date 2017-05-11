<?php
	if (session_id() == "") session_start();
  include_once '../scripts/DB.php';
  include_once '../algorithm/find.php';
  include_once '../algorithm/analyze.php';
  include_once '../algorithm/distribute.php';
  include_once '../scripts/importCal.php';

  $db = new DB();
  $calendarStudy = $db -> select("SELECT STUDY FROM calendar WHERE ID ='$_SESSION[uuid]'");
  $calendar = $calendarStudy[0]["STUDY"];

  //$calendar = downloadFile($calendarStudy);

  $calendarPersonal = $db -> select("SELECT PERSONAL FROM calendar WHERE ID ='$_SESSION[uuid]'");
  $calendarPersonal = $calendarPersonal[0]["PERSONAL"];

  $calendarHabits = $db -> select("SELECT HABITS FROM calendar WHERE ID ='$_SESSION[uuid]'");
  $calendarHabits = $calendarHabits[0]["HABITS"];

  if(isset($calendarPersonal) && $calendarPersonal !== "") {
		$calendar = modify($calendar, $calendarPersonal);
  }

  if(isset($calendarHabits) && $calendarHabits !== "") {
		$calendar = modify($calendar, $calendarHabits);
    }

  $calendarRoutines = $db -> select("SELECT ROUTINES FROM data WHERE ID ='$_SESSION[uuid]'");
  $calendarRoutines = $calendarRoutines[0]["ROUTINES"];

  $calendarCourses = $db -> select("SELECT COURSES FROM data WHERE ID ='$_SESSION[uuid]'");
  $calendarCourses = $calendarCourses[0]["COURSES"];

  $calendar = free_time_with_events($calendar);
  $calendar = analyze($calendar, $calendarRoutines);

  $calendar = distribute($calendar, $calendarCourses, $calendarRoutines);

  $db -> query("UPDATE calendar SET CURRENT=".$db->quote($calendar) ." WHERE ID='$_SESSION[uuid]'");

	  echo "<h3>Algorithm is finished</h3>";

?>
